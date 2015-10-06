/**
 * $Id: editor_plugin_src.js 201 2007-02-12 15:56:56Z spocke $
 *
 * @author Moxiecode
 * @copyright Copyright © 2004-2008, Moxiecode Systems AB, All rights reserved.
 * NPDS 2001 - 2013
 */

(function() {
   // Load plugin specific language pack
   tinymce.PluginManager.requireLangPack('npds');

   tinymce.create('tinymce.plugins.NPDSPlugin', {
      /**
       * Initializes the plugin, this will be executed after the plugin has been created.
       * This call is done before the editor instance has finished it's initialization so use the onInit event
       * of the editor instance to intercept that event.
       *
       * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
       * @param {string} url Absolute URL to where the plugin is located.
       */
      init : function(ed, url) {
         ed.addCommand('NPDS_img', function() {
            ed.windowManager.open({
               title : 'itmp',
               file : 'modules.php?ModPath=f-manager&ModStart=f-manager&FmaRep=bank-public',
               width : 450,
               height : 500,
               resizable : 'yes',
               scrollbars : 'yes',
               inline : 1
            }, {
               plugin_url : url, // Plugin absolute URL
               some_custom_arg : '' // Custom argument
            });
         });
         // Register button
         ed.addButton('npds_img', {
            title : 'npds.npds_img_desc',
            cmd : 'NPDS_img',
            image : url + '/images/npds_img.gif'
         });

         ed.addCommand('NPDS_Perso', function() {
            ed.windowManager.open({
               title : 'ptmp',
               file : 'modules.php?ModPath=f-manager&ModStart=f-manager&FmaRep=bank-membre',
               width : 450,
               height : 500,
               resizable : 'yes',
               scrollbars : 'yes',
               inline : 1
            }, {
               plugin_url : url, // Plugin absolute URL
               some_custom_arg : '' // Custom argument
            });
         });
         // Register button
         ed.addButton('npds_perso', {
            title : 'npds.npds_perso_desc',
            cmd : 'NPDS_Perso',
            image : url + '/images/npds_perso.gif'
         });
         ed.addCommand('NPDS_GPerso', function() {
            ed.windowManager.open({
               title : 'gtmp',
               file : 'modules.php?ModPath=f-manager&ModStart=f-manager&FmaRep=bank-groupe'+tinymce.settings.tiny_mce_groupe,
               width : 450,
               height : 500,
               resizable : 'yes',
               scrollbars : 'yes',
               inline : 1
            }, {
               plugin_url : url, // Plugin absolute URL
               some_custom_arg : '' // Custom argument
            });
         });
         // Register button
         ed.addButton('npds_gperso', {
            title : 'npds.npds_perso_desc',
            cmd : 'NPDS_GPerso',
            image : url + '/images/npds_perso.gif'
         });

         ed.addCommand('NPDS_Mns', function() {
            ed.windowManager.open({
               title : 'mnstmp',
               file : 'modules.php?ModPath=f-manager&ModStart=f-manager&FmaRep=minisite-lec',
               width : 450,
               height : 500,
               resizable : 'yes',
               scrollbars : 'yes',
               inline : 1
            }, {
               plugin_url : url, // Plugin absolute URL
               some_custom_arg : '' // Custom argument
            });
         });
         // Register button
         ed.addButton('npds_mns', {
            title : 'npds.npds_mns_desc',
            cmd : 'NPDS_Mns',
            image : url + '/images/npds_mns.gif'
         });
         ed.addCommand('NPDS_GMns', function() {
            ed.windowManager.open({
               title : 'gmnstmp',
               file : 'modules.php?ModPath=f-manager&ModStart=f-manager&FmaRep=minisite-lec-groupe'+tinymce.settings.tiny_mce_groupe,
               width : 450,
               height : 500,
               resizable : 'yes',
               scrollbars : 'yes',
               inline : 1
            }, {
               plugin_url : url, // Plugin absolute URL
               some_custom_arg : '' // Custom argument
            });
         });
         // Register button
         ed.addButton('npds_gmns', {
            title : 'npds.npds_mns_desc',
            cmd : 'NPDS_GMns',
            image : url + '/images/npds_mns.gif'
         });

         ed.addCommand('NPDS_Upl', function() {
            ed.windowManager.open({
               title : 'utmp',
               file : 'modules.php?ModPath=upload&ModStart=include_editeur/upload_editeur2&apli=editeur',
               width : 350,
               height : 150,
               resizable : 'no',
               scrollbars : 'no',
               inline : 1
            }, {
               plugin_url : url, // Plugin absolute URL
               some_custom_arg : '' // Custom argument
            });
         });
         // Register button
         ed.addButton('npds_upl', {
            title : 'npds.npds_upl_desc',
            cmd : 'NPDS_Upl',
            image : url + '/images/npds_upload.gif'
         });
         ed.addCommand('NPDS_Gupl', function() {
            ed.windowManager.open({
               title : 'gutmp',
               file : 'modules.php?ModPath=upload&ModStart=include_editeur/upload_editeur2&apli=editeur'+tinymce.settings.tiny_mce_groupe,
               width : 350,
               height : 150,
               resizable : 'no',
               scrollbars : 'no',
               inline : 1
            }, {
               plugin_url : url, // Plugin absolute URL
               some_custom_arg : '' // Custom argument
            });
         });
         // Register button
         ed.addButton('npds_gupl', {
            title : 'npds.npds_upl_desc',
            cmd : 'NPDS_Gupl',
            image : url + '/images/npds_upload.gif'
         });

         ed.addCommand('NPDS_MetaL', function() {
            ed.windowManager.open({
               title : 'mtmp',
               file : 'modules.php?ModPath=meta-lang&ModStart=adv-meta_lang-doc',
               width : 700,
               height : 500,
               resizable : 'yes',
               scrollbars : 'yes',
               inline : 1
            }, {
               plugin_url : url, // Plugin absolute URL
               some_custom_arg : '' // Custom argument
            });
         });
         // Register button
         ed.addButton('npds_metal', {
            title : 'npds.npds_metal_desc',
            cmd : 'NPDS_MetaL',
            image : url + '/images/npds_metal.gif'
         });

         ed.addCommand('NPDS_Plug', function() {
            ed.windowManager.open({
               title : 'ptmp',
               file : 'editeur/tiny_mce/plugins/npds/template.htm',
               width : 400,
               height : 350,
               resizable : 'yes',
               scrollbars : 'no',
               inline : 1
            }, {
               plugin_url : url, // Plugin absolute URL
               some_custom_arg : '' // Custom argument
            });
         });
         // Register button
         ed.addButton('npds_plug', {
            title : 'npds.npds_plug_desc',
            cmd : 'NPDS_Plug',
            image : url + '/images/npds_plug.gif'
         });
      },

      /**
       * Creates control instances based in the incomming name. This method is normally not
       * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
       * but you sometimes need to create more complex controls like listboxes, split buttons etc then this
       * method can be used to create those.
       *
       * @param {String} n Name of the control to create.
       * @param {tinymce.ControlManager} cm Control manager to use inorder to create new control.
       * @return {tinymce.ui.Control} New control instance or null if no control was created.
       */
       createControl: function(n, cm) {
        switch (n) {
            case 'npds_langue':
                var mlb = cm.createListBox('ltpm', {
                     title : 'npds.npds_what_desc',
                     onselect : function(v) {
                         tinyMCE.execCommand('mceInsertContent',true, v);
                     }
                });
                // Add some values to the list box
                mlb.add('npds.npds_french_desc', '[french] [/french]');
                mlb.add('npds.npds_frenchonly_desc', '[!french] [/french]');
                mlb.add('npds.npds_english_desc', '[english] [/english]');
                mlb.add('npds.npds_englishonly_desc', '[!english] [/english]');
                mlb.add('npds.npds_spanish_desc', '[spanish] [/spanish]');
                mlb.add('npds.npds_spanishonly_desc', '[!spanish] [/spanish]');
                mlb.add('npds.npds_chinese_desc', '[chinese] [/chinese]');
                mlb.add('npds.npds_chineseonly_desc', '[!chinese] [/chinese]');
                mlb.add('npds.npds_german_desc', '[german] [/german]');
                mlb.add('npds.npds_germanonly_desc', '[!german] [/german]');

                // Return the new listbox instance
                return mlb;
        }
        return null;
      },

      /**
       * Returns information about the plugin as a name/value array.
       * The current keys are longname, author, authorurl, infourl and version.
       *
       * @return {Object} Name/value array containing information about the plugin.
       */
      getInfo : function() {
         return {
            longname : 'NPDS',
            author : 'developpeur',
            authorurl : 'http://www.npds.org',
            infourl : 'http://www.npds.org',
            version : "REv 13"
         };
      }
   });
 
   // Register plugin
   tinymce.PluginManager.add('npds', tinymce.plugins.NPDSPlugin);
})();