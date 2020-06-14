/**
 * @package J2XML
 * @subpackage plg_j2xml_users
 * @version 3.7.9
 * @since 3.7.4
 * 
 * @author Helios Ciancio <info (at) eshiol (dot) it>
 * @link http://www.eshiol.it
 * @copyright Copyright (C) 2016 - 2019 Helios Ciancio. All Rights Reserved
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL v3 J2XML is free
 *          software. This version may have been modified pursuant to the GNU
 *          General Public License, and as distributed it includes or is
 *          derivative of works licensed under the GNU General Public License or
 *          other free or open source software licenses.
 */

// Avoid `console` errors in browsers that lack a console.
(function() {
	var methods = [ 'assert', 'clear', 'count', 'debug', 'dir', 'dirxml',
			'error', 'exception', 'group', 'groupCollapsed', 'groupEnd',
			'info', 'log', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
			'timeStamp', 'trace', 'warn' ];
	console = window.console = window.console || {};
	methods.forEach(function(method) {
		if (!console[method]) {
			console[method] = function() {
			};
		}
	});
}());

if (typeof (eshiol) === 'undefined') {
	eshiol = {};
}

if (typeof (eshiol.j2xml) === 'undefined') {
	eshiol.j2xml = {};
}

if (typeof (eshiol.j2xml.convert) === 'undefined') {
	eshiol.j2xml.convert = [];
}

eshiol.j2xml.users = {};
eshiol.j2xml.users.version = '3.7.9';
eshiol.j2xml.users.requires = '19.2.323';

console.log('J2XML - Users Importer v' + eshiol.j2xml.users.version);

/**
 * 
 * @param {}
 *            root
 * @return {}
 */
eshiol.j2xml.convert
		.push(function(xml) {
			console.log('eshiol.j2xml.convert.users');
			if (versionCompare(eshiol.j2xml.version,
					eshiol.j2xml.users.requires) < 0) {
				eshiol.renderMessages({
					'error' : [ 'J2XML - Users Importer v'
							+ eshiol.j2xml.users.version
							+ ' requires J2XML v3.7.192' ]
				});
				return false;
			}

			console.log(xml);
			// var lines = xml.split(/\r?\n/);
			// var header = CSVToArray(lines[0], ";");
			var csv = CSVToArray(xml, ",");

			var header = csv[0];
			console.log(header);
			var cols = [];

			for (var i = 0; i < header.length; i++) {
				console.log(header[i] + ': ' + i);
				if ((header[i] == 'group')
						|| (header[i].substring(0, 6) == 'group.')) {
					if (typeof cols['group'] === 'undefined') {
						cols['group'] = [];
					}
					cols['group'].push(i);
				} else if (header[i].substring(0, 6) == 'field.') {
					if (typeof cols['fields'] === 'undefined') {
						cols['fields'] = [];
					}
					cols['fields'][header[i].substring(6)] = i;
				} else if (header[i].substring(0, 8) == 'profile.') {
					if (typeof cols['profiles'] === 'undefined') {
						cols['profiles'] = [];
					}
					cols['profiles'][header[i].substring(8)] = i;
				} else {
					cols[header[i]] = i;
				}
			}

			Object.keys(cols).forEach(function(key, index) {
				if (Array.isArray(this[key])) {
					Object.keys(this[key]).forEach(function(key1, index) {
						console.log(key + '.' + key1 + ': ' + this[key1]);
					}, this[key]);
				} else {
					console.log(key + ': ' + this[key]);
				}
			}, cols);

			if ((cols['username'] == undefined) || (cols['name'] == undefined)
					|| (cols['email'] == undefined)) {
				console.log('invalid CSV file');
				return xml;
			}

			xml = '';
			for (var i = 1; i < csv.length; i++) {
				console.log(csv[i]);

				var x = '';
				x += "\t<user>\n";
				x += "\t\t<id>0</id>\n";
				x += "\t\t<name><![CDATA[" + csv[i][cols['name']]
						+ "]]></name>\n";
				x += "\t\t<username><![CDATA[" + csv[i][cols['username']]
						+ "]]></username>\n";
				x += "\t\t<email><![CDATA[" + csv[i][cols['email']]
						+ "]]></email>\n";
				if ((cols['password'] != undefined)
						&& (csv[i][cols['password']] != undefined)) {
					x += "\t\t<password><![CDATA[" + csv[i][cols['password']]
							+ "]]></password>\n";
				} else if ((cols['password_clear'] != undefined)
						&& (csv[i][cols['password_clear']] != undefined)) {
					x += "\t\t<password_clear><![CDATA["
							+ csv[i][cols['password_clear']]
							+ "]]></password_clear>\n";
				} else {
					password_clear = Math.random() // Generate random number,
					// eg: 0.123456
					.toString(36) // Convert to base-36 : "0.4fzyo82mvyr"
					.slice(-8); // Cut off last 8 characters : "yo82mvyr"
					x += "\t\t<password_clear><![CDATA[" + password_clear
							+ "]]></password_clear>\n";
				}
				x += "\t\t<requireReset>";
				if (cols['requireReset'] != undefined)
				{
					x += eshiol.j2xml.users.requireReset;
				}
				else if (cols['requireReset'] == null)
				{
					x += eshiol.j2xml.users.requireReset;
				}
				else if (cols['requireReset'] == 0)
				{
					x += 0;
				}
				else if (cols['requireReset'] == 1)
				{
					x += 1;
				}
				else
				{
					x += eshiol.j2xml.users.requireReset;
				}
				x += "</requireReset>\n";
				x += "\t\t<block>0</block>\n";
				x += "\t\t<sendEmail>0</sendEmail>\n";
				x += "\t\t<registerDate><![CDATA[" + (new Date().toString())
						+ "]]></registerDate>\n";
				x += "\t\t<lastvisitDate><![CDATA[0000-00-00 00:00:00]]></lastvisitDate>\n";
				x += "\t\t<activation/>\n";
				x += "\t\t<params><![CDATA[{\"admin_style\":\"\",\"admin_language\":\"\",\"language\":\"\",\"editor\":\"\",\"helpsite\":\"\",\"timezone\":\"\"}]]></params>\n";
				x += "\t\t<lastResetTime><![CDATA[0000-00-00 00:00:00]]></lastResetTime>\n";
				x += "\t\t<resetCount>0</resetCount>\n";
				x += "\t\t<otpKey/>\n";
				x += "\t\t<otep/>\n";

				var groups = [];
				if (cols['groups'] !== undefined) {
					JSON.parse(csv[i][cols['groups']]).forEach(function(group) {
						if (Array.isArray(group)) {
							groups.push(JSON.stringify(group));
						} else {
							groups.push(group);
						}
					});
				}
				if (cols['group'] !== undefined) {
					for (var j = 0; j < cols['group'].length; j++) {
						groups.push(csv[i][cols['group'][j]]);
					}
				}
				groups = (function(a) {
					var seen = {};
					return a.filter(function(item) {
						return seen.hasOwnProperty(item) ? false
								: (seen[item] = true);
					});
				})(groups);
				if (groups.length == 1) {
					x += "\t<group><![CDATA[" + groups[0] + "]]></group>\n";
				} else if (groups.length > 1) {
					x += "\t\t<grouplist>\n";
					groups
							.forEach(function(group) {
								x += "\t\t\t<group><![CDATA[" + group
										+ "]]></group>\n";
							});
					x += "\t\t</grouplist>\n";
				}

				var fields = [];
				if (cols['fields'] !== undefined) {
					Object.keys(cols['fields']).forEach(function(key, index) {
						fields[key] = csv[i][this[key]];
					}, cols['fields']);
				}
				var n = Object.keys(fields).length;
				if (n == 1) {
					Object.keys(cols['fields']).forEach(
							function(key, index) {
								x += "\t\t<field>\n";
								x += "\t\t\t<name><![CDATA[[\"" + key
										+ "\"]]]></name>\n";
								x += "\t\t\t<value><![CDATA[[\"" + this[key]
										+ "\"]]]></value>\n";
								x += "\t\t</field>\n";
							}, fields);
				} else if (n > 1) {
					x += "\t\t<fieldlist>\n";
					Object.keys(cols['fields']).forEach(
							function(key, index) {
								x += "\t\t\t<field>\n";
								x += "\t\t\t\t<name><![CDATA[[\"" + key
										+ "\"]]]></name>\n";
								x += "\t\t\t\t<value><![CDATA[[\"" + this[key]
										+ "\"]]]></value>\n";
								x += "\t\t\t</field>\n";
							}, fields);
					x += "\t\t</fieldlist>\n";
				}

				var profiles = [];
				if (cols['profiles'] !== undefined) {
					Object.keys(cols['profiles']).forEach(function(key, index) {
						profiles[key] = csv[i][this[key]];
					}, cols['profiles']);
				}
				var n = Object.keys(profiles).length;
				if (n == 1) {
					Object.keys(cols['profiles']).forEach(
							function(key, index) {
								x += "\t\t<profile>\n";
								x += "\t\t\t<name><![CDATA[[\"" + key
										+ "\"]]]></name>\n";
								x += "\t\t\t<value><![CDATA[[\"" + this[key]
										+ "\"]]]></value>\n";
								x += "\t\t</profile>\n";
							}, profiles);
				} else if (n > 1) {
					x += "\t\t<profilelist>\n";
					Object.keys(cols['profiles']).forEach(
							function(key, index) {
								x += "\t\t\t<profile>\n";
								x += "\t\t\t\t<name><![CDATA[[\"" + key
										+ "\"]]]></name>\n";
								x += "\t\t\t\t<value><![CDATA[[\"" + this[key]
										+ "\"]]]></value>\n";
								x += "\t\t\t</profile>\n";
							}, profiles);
					x += "\t\t</profilelist>\n";
				}

				x += "\t</user>\n";
				xml += x;
			}

			return "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<j2xml version=\"19.2.0\">\n"
					+ xml + "</j2xml>";
		});
