      //<![CDATA[
      /* ce script assure : 
      responsivité des anciens site : forçage des dimensions par la classe img-fluid,
      paramétrages de plugins :  tolltips, popover, toggle, boostrap table, highlight
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
         $("#ban img,#art_preview img,#online_user_journal img,#art_sect img, iframe").addClass("img-fluid");
         $(".fo-post-mes img").addClass("img-fluid");//not sure if usefull to late ...

         $('a.arrow-toggle').on('click', function(e){
         $('a.arrow-toggle i').toggleClass('fa-caret-down , fa-caret-up', 6000);
         })

         $('[data-toggle="tooltip"]').tooltip({container:'#corps'});
         $('[data-toggle="popover"]').popover();
         // fix bug tooltip in table
         $('table').on('all.bs.table', function (e, name, args) {
            $('[data-toggle="tooltip"]').tooltip();
            $('[data-toggle="popover"]').popover();
         });
      });
      
      // ==> colorisation syntaxique du code //
      hljs.configure({useBR: true});
      $('pre code').each(function(i, block) {
        hljs.highlightBlock(block);
      });
      //]]>
