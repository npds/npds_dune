//<![CDATA[
      /* ce script assure :
      responsivité des anciens site : forçage des dimensions par la classe img-fluid,
      paramétrages de plugins :  tooltips, popover, toggle, boostrap table, highlight
      */
      // ==> choix icon boostrap table //
         window.icons = {
            refresh: 'fa-refresh fa-lg',
            toggle: 'fa-toggle-on',
            toggleOff: 'fa-toggle-off',
            toggleOn: 'fa-toggle-on',
            columns: 'fa-th-list',
            detailOpen: 'fa-plus-square-o',
            detailClose: 'fa-minus-square-o',
            fullscreen: "fa-arrows-alt"
         };
      // <== choix icon boostrap table //
      // ==> tri boostrap table //
         function htmlSorter(a, b) {
            var a = $(a).text();
            var b = $(b).text();
            if (a < b) return -1;
            if (a > b) return 1;
            return 0;
         }
      // <== tri boostrap table //

         $(document).ready(function(){
            // responsive old data
            $('.article_texte img,.ibid_descr img').addClass('img-fluid');
            $('#ban img,#art_preview img,#online_user_journal img,#art_sect img,#print_sect img').addClass('img-fluid');

            $('.fo-post-mes img').addClass('img-fluid');//not sure if usefull to late ...
            // icon toggle
            $('a[data-bs-toggle="collapse"]').click(function () {
               $(this).find('i.toggle-icon').toggleClass('fa-caret-up fa-caret-down',6000);
            })
            $('a[data-bs-toggle="collapse"]').click(function () {
               $(this).find('i.togglearbr-icon').toggleClass('fa-level-up-alt fa-level-down-alt',6000);
            })
            // initialisation tooltip et popover (qui ferme au prochain click)
            $('[data-bs-toggle="tooltip"]').tooltip({container:'body'});
            $('[data-bs-toggle="popover"]').popover();
            $('.popover-dismiss').popover({ trigger: 'click'});
            // fix bug tooltip in table
            $('table').on('all.bs.table', function (e, name, args) {
               $('[data-bs-toggle="tooltip"]').tooltip();
               $('[data-bs-toggle="popover"]').popover();
            });
 
         });
         (function(t) {
            "use strict";
            t((function() {
               t(".tooltipbyclass").tooltip();
            }))
         })(jQuery);
      //]]>
