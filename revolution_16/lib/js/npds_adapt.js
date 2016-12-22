      //<![CDATA[
      /* ce script assure : 
      responsivité des anciens site : forçage des dimensions par la classe img-fluid,
      paramétrages de plugins :  tooltips, popover, toggle, boostrap table, highlight
      */

      // ==> choix icon boostrap table //
         window.icons = {
            refresh: 'fa-refresh fa-lg',
            toggle: 'fa-toggle-on fa-lg',
            columns: 'fa-th-list fa-lg',
            detailOpen: 'fa-plus-square-o',
            detailClose: 'fa-minus-square-o'
         };
      // <== choix icon boostrap table //

      $(document).ready(function(){
         // responsive old data
         $(".article_texte img,.ibid_descr img").addClass("img-fluid");
         $("#ban img,#art_preview img,#online_user_journal img,#art_sect img").addClass("img-fluid");
         $("iframe").addClass("embed-responsive-item");
         
         
         $(".fo-post-mes img").addClass("img-fluid");//not sure if usefull to late ...
         // icon toggle 
         $('a[data-toggle="collapse"]').click(function () {
            $(this).find('i.toggle-icon').toggleClass('fa-caret-up fa-caret-down',6000);
         })
         $('a[data-toggle="collapse"]').click(function () {
            $(this).find('i.togglearbr-icon').toggleClass('fa-level-up fa-level-down',6000);
         })
         // initialisation tooltip et popover
         $('[data-toggle="tooltip"]').tooltip({container:'#corps'});
         $('[data-toggle="popover"]').popover();
         // fix bug tooltip in table
         $('table').on('all.bs.table', function (e, name, args) {
            $('[data-toggle="tooltip"]').tooltip();
            $('[data-toggle="popover"]').popover();
         });
      });
      
      // ==> colorisation syntaxique du code //
      $('pre code').each(function(i, block) {
        hljs.highlightBlock(block);
        hljs.configure({useBR: true});
      });
      //]]>
