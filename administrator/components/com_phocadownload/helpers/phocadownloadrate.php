<?php
/*
 * @package		Joomla.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

class PhocaDownloadRateHelper
{
	function updateVoteStatisticsFile( $fileid ) {

		$db = JFactory::getDBO();

		// Get AVG and COUNT
		$query = 'SELECT COUNT(vs.id) AS count, AVG(vs.rating) AS average'
				.' FROM #__phocadownload_file_votes AS vs'
			    .' WHERE vs.fileid = '.(int) $fileid;
		//		.' AND vs.published = 1';
		$db->setQuery($query, 0, 1);
		$votesStatistics = $db->loadObject();
		// if no count, set the average to 0
		if($votesStatistics->count == 0) {
			$votesStatistics->count = (int)0;
			$votesStatistics->average = (float)0;
		}

		if (isset($votesStatistics->count) && isset($votesStatistics->average)) {
			// Insert or update
			$query = 'SELECT vs.id AS id'
					.' FROM #__phocadownload_file_votes_statistics AS vs'
				    .' WHERE vs.fileid = '.(int) $fileid;
			$db->setQuery($query, 0, 1);
			$votesStatisticsId = $db->loadObject();

			// Yes, there is id (UPDATE) x No, there isn't (INSERT)
			if (!empty($votesStatisticsId->id)) {

				$query = 'UPDATE #__phocadownload_file_votes_statistics'
					.' SET count = ' .(int)$votesStatistics->count
					.' , average = ' .(float)$votesStatistics->average
				    .' WHERE fileid = '.(int) $fileid;
				$db->setQuery($query);

				if (!$db->execute()) {

					throw new Exception('Database Error Voting 1', 500);
					return false;
				}

			} else {

				$query = 'INSERT into #__phocadownload_file_votes_statistics'
					.' (id, fileid, count, average)'
				    .' VALUES (null, '.(int)$fileid
					.' , '.(int)$votesStatistics->count
					.' , '.(float)$votesStatistics->average
					.')';
				$db->setQuery($query);

				if (!$db->execute()) {

					throw new Exception('Database Error Voting 2', 500);
					return false;
				}

			}
		} else {
			return false;
		}
		return true;
	}

	function getVotesStatisticsFile($id) {

		$db = JFactory::getDBO();
		$query = 'SELECT vs.count AS count, vs.average AS average'
				.' FROM #__phocadownload_file_votes_statistics AS vs'
			    .' WHERE vs.fileid = '.(int) $id;
		$db->setQuery($query, 0, 1);
		$votesStatistics = $db->loadObject();

		return $votesStatistics;
	}

	function checkUserVoteFile($fileid, $userid) {

		$db = JFactory::getDBO();
		$query = 'SELECT v.id AS id'
			    .' FROM #__phocadownload_file_votes AS v'
			    .' WHERE v.fileid = '. (int)$fileid
				.' AND v.userid = '. (int)$userid;
		$db->setQuery($query, 0, 1);
		$checkUserVote = $db->loadObject();
		if ($checkUserVote) {
			return true;
		}
		return false;
	}


	public function renderRateFile($id, $displayRating, $small = 1, $refresh = false) {

		$user					= JFactory::getUser();
		$neededAccessLevels		= PhocaDownloadAccess::getNeededAccessLevels();
		$access					= PhocaDownloadAccess::isAccess($user->getAuthorisedViewLevels(), $neededAccessLevels);


		if ($small == 1) {
			$smallO = '-small';
			$ratio = 18;
		} else {
			$smallO = '';
			$ratio = 22;
		}

		$o = '';

		//.$rating['urlvote'].$amp.'controller=detail&task=rate&rating=1
		//$amp 	= PhocaDownloadUtils::setQuestionmarkOrAmp($rating['urlvote']);
		$href	= 'javascript:void(0);';

		if ((int)$displayRating != 1) {
			return '';
		} else {

			$rating['alreadyratedfile']	= self::checkUserVoteFile( (int)$id, (int)$user->id );

			$rating['notregisteredfile'] 	= true;
			//$rating['usernamefile']		= '';
			if ($access > 0) {
				$rating['notregisteredfile'] 	= false;
				$rating['usernamefile']			= $user->name;
			}

			$rating['votescountfile'] 	= 0;
			$rating['votesaveragefile'] = 0;
			$rating['voteswidthfile'] 	= 0;
			$votesStatistics	= self::getVotesStatisticsFile((int)$id);
			if (!empty($votesStatistics->count)) {
				$rating['votescountfile'] = $votesStatistics->count;
			}
			if (!empty($votesStatistics->average)) {
				$rating['votesaveragefile'] = $votesStatistics->average;
				if ($rating['votesaveragefile'] > 0) {
					$rating['votesaveragefile'] 	= round(((float)$rating['votesaveragefile'] / 0.5)) * 0.5;
					$rating['voteswidthfile']		= $ratio * $rating['votesaveragefile'];
				} else {
					$rating['votesaveragefile'] 	= (int)0;// not float displaying
				}
			}

			// Leave message for already voted images
			//$vote = JFactory::getApplication()->input->get('vote', 0, '', 'int');
			$voteMsg = JText::_('COM_PHOCADOWNLOAD_RATING_ALREADY_RATED_FILE');
			//if ($vote == 1) {
			//	$voteMsg = JText::_('COM_PHOCADOWNLOAD_ALREADY_RATED_FILE_THANKS');
			//}

			$rating['votestextimg'] = 'VOTE';
			if ((int)$rating['votescountfile'] > 1) {
				$rating['votestextimg'] = 'VOTES';
			}

			$o .= '<div style="float:left;"><strong>'
					. JText::_('COM_PHOCADOWNLOAD_RATING'). '</strong>: ' . $rating['votesaveragefile'] .' / '
					.$rating['votescountfile'] . ' ' . JText::_('COM_PHOCADOWNLOAD_'.$rating['votestextimg']). '&nbsp;&nbsp;</div>';

			if ($rating['alreadyratedfile']) {
				$o .= '<div style="float:left;"><ul class="star-rating'.$smallO.'">'
						.'<li class="current-rating" style="width:'.$rating['voteswidthfile'].'px"></li>'
						.'<li><span class="star1"></span></li>';

				for ($i = 2;$i < 6;$i++) {
					$o .= '<li><span class="stars'.$i.'"></span></li>';
				}
				$o .= '</ul></div>';

				$or ='<div class="pd-result" id="pdresult'.(int)$id.'" style="float:left;margin-left:5px">'.JText::_('COM_PHOCADOWNLOAD_RATING_ALREADY_RATED_FILE').'</div>';

			} else if ($rating['notregisteredfile']) {

				$o .= '<div style="float:left;"><ul class="star-rating'.$smallO.'">'
						.'<li class="current-rating" style="width:'.$rating['voteswidthfile'].'px"></li>'
						.'<li><span class="star1"></span></li>';

				for ($i = 2;$i < 6;$i++) {
					$o .= '<li><span class="stars'.$i.'"></span></li>';
				}
				$o .= '</ul></div>';

				$or ='<div class="pd-result" id="pdresult'.(int)$id.'" style="float:left;margin-left:5px">'.JText::_('COM_PHOCADOWNLOAD_ONLY_REGISTERED_LOGGED_RATE_FILE').'</div>';

			} else {

				$o .= '<div style="float:left;"><ul class="star-rating'.$smallO.'">'
						.'<li class="current-rating" style="width:'.$rating['voteswidthfile'].'px"></li>'
						.'<li><a href="'.$href.'" onclick="pdRating('.(int)$id.', 1)" title="1 '. JText::_('COM_PHOCADOWNLOAD_STAR_OUT_OF').' 5" class="star1">1</a></li>';

				for ($i = 2;$i < 6;$i++) {
					$o .= '<li><a href="'.$href.'" onclick="pdRating('.(int)$id.', '.$i.')" title="'.$i.' '. JText::_('COM_PHOCADOWNLOAD_STARS_OUT_OF').' 5" class="stars'.$i.'">'.$i.'</a></li>';
				}
				$o .= '</ul></div>';

				$or ='<div class="pd-result" id="pdresult'.(int)$id.'" style="float:left;margin-left:5px"></div>';
			}



		}

		if ($refresh == true) {
			return $o;//we are in Ajax, return only content of pdvoting div
		} else {
			return '<div id="pdvoting'.(int)$id.'">'.$o.'</div>' .$or ;//not in ajax, return the contend in div
		}


	}

	public function renderRateFileJS($small = 1) {

		$document	 = JFactory::getDocument();
		$url		  = 'index.php?option=com_phocadownload&view=ratingfilea&task=rate&format=json&'.JSession::getFormToken().'=1';
		$urlRefresh		= 'index.php?option=com_phocadownload&view=ratingfilea&task=refreshrate&small='.$small.'&format=json&'.JSession::getFormToken().'=1';
		$imgLoadingUrl = JURI::base(). 'components/com_phocadownload/assets/images/icon-loading2.gif';
		$imgLoadingHTML = '<img src="'.$imgLoadingUrl.'" alt="" />';
		$js  = '<script type="text/javascript">' . "\n" . '<!--' . "\n";
		//$js .= 'window.addEvent("domready",function() {
		$js .= '
		function pdRating(id, vote) {
		
			var result 			= "pdresult" + id;
			var resultvoting 	= "pdvoting" + id;
			var pdRequest = new Request.JSON({
			url: "'.$url.'",
			method: "post",
			
			onRequest: function(){
				$(result).set("html", "'.addslashes($imgLoadingHTML).'");
			  },
			
			onComplete: function(jsonObj) {
				try {
					var r = jsonObj;
				} catch(e) {
					var r = false;
				}
			
				if (r) {
					if (r.error == false) {
						$(result).set("text", jsonObj.message);
						
						// Refreshing Voting
						var pdRequestRefresh = new Request.JSON({
							url: "'.$urlRefresh.'",
							method: "post",
							
							onComplete: function(json2Obj) {
								try {
									var rr = json2Obj;
								} catch(e) {
									var rr = false;
								}
							
								if (rr) {
									$(resultvoting).set("html", json2Obj.message);
								} else {
									$(resultvoting).set("text", "'.JText::_('COM_PHOCADOWNLOAD_ERROR_REQUESTING_RATING').'");
								}
							},
						
							onFailure: function() {
								$(resultvoting).set("text", "'.JText::_('COM_PHOCADOWNLOAD_ERROR_REQUESTING_RATING').'");
							}
						})
				  
						pdRequestRefresh.send({
							data: {"ratingId": id, "ratingVote": vote, "format":"json"}
						});
						//End refreshing voting
						
					} else {
						$(result).set("html", r.error);
					}
				} else {
					$(result).set("text", "'.JText::_('COM_PHOCADOWNLOAD_ERROR_REQUESTING_RATING').'");
				}
			},
			
			onFailure: function() {
				$(result).set("text", "'.JText::_('COM_PHOCADOWNLOAD_ERROR_REQUESTING_RATING').'");
			
			}
			})
	  
			pdRequest.send({
				data: {"ratingId": id, "ratingVote": vote, "format":"json"},
			});
  
		};';

		//$js .= '});';

		$js .= "\n" . '//-->' . "\n" .'</script>';
		$document->addCustomTag($js);

	}
}
?>
