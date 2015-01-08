var SITE_BASE = "/";

function getAjaxLoader(){
   return '<div class="text-center mb20"><img src="/public/bracket/images/loaders/loader6.gif" alt=""></div>';
}

function addGritterNotification(gritterObject){
   jQuery.gritter.add({
      title: gritterObject.title || null,
      text: gritterObject.text,
      class_name: gritterObject.class_name || 'growl-info',
      image: gritterObject.image || null,
      sticky: gritterObject.sticky || false,
      time: gritterObject.time || '5000'
   });
}

function moneyToNumber(money){
   return money.replace(/[$.',\s]/g, '');
}

function numberToMoney(valor){
   valor = valor.toString();
   if(isNaN(valor)){
      valor = valor.replace(/[$.',\s]/g, '');
      if(isNaN(valor)){
         return "";
      }
   }
   var decimals = Math.ceil(valor.length / 3) - 1;
   var money = '';
   var dots = new Array ("'", ".", "'", ".", "'", ".");
   i = decimals + 1;
   if(i > 0 && valor.length > 3){
      while(i > 0){
         if(i == 1){
            money = money.concat(dots[i], valor.slice(i * -3));
         }else if(i == (decimals + 1)){
            money = money.concat('$ ' + valor.slice((i*-3), ((i-1)*-3)));
         }else{
            money = money.concat(dots[i], valor.slice((i*-3), ((i-1)*-3)));
         }
         i--;
      }
   }else{
      money = '$ ' + valor;
   }
   return money;
}

jQuery(window).load(function() {
   // Page Preloader
   jQuery('#status').fadeOut();
   jQuery('#preloader').delay(350).fadeOut(function(){
      jQuery('body').delay(350).css({'overflow':'visible'});
   });
   jQuery('.gritter_message').trigger('fullyLoaded');
});

jQuery(document).ready(function() {
   // Toggle Left Menu
   jQuery('.nav-parent > a').click(function() {
      
      var parent = jQuery(this).parent();
      var sub = parent.find('> ul');
      
      // Dropdown works only when leftpanel is not collapsed
      if(!jQuery('body').hasClass('leftpanel-collapsed')) {
         if(sub.is(':visible')) {
            sub.slideUp(200, function(){
               parent.removeClass('nav-active');
               jQuery('.mainpanel').css({height: ''});
               adjustmainpanelheight();
            });
         } else {
            closeVisibleSubMenu();
            parent.addClass('nav-active');
            sub.slideDown(200, function(){
               adjustmainpanelheight();
            });
         }
      }
      return false;
   });
   
   function closeVisibleSubMenu() {
      jQuery('.nav-parent').each(function() {
         var t = jQuery(this);
         if(t.hasClass('nav-active')) {
            t.find('> ul').slideUp(200, function(){
               t.removeClass('nav-active');
            });
         }
      });
   }
   
   function adjustmainpanelheight() {
      // Adjust mainpanel height
      var docHeight = jQuery(document).height();
      if(docHeight > jQuery('.mainpanel').height())
         jQuery('.mainpanel').height(docHeight);
   }

   // Tooltip
   jQuery('.tooltips').tooltip({ container: 'body'});
   
   // Popover
   jQuery('.popovers').popover();
   
   // Close Button in Panels
   jQuery('.panel .panel-close').click(function(){
      jQuery(this).closest('.panel').fadeOut(200);
      return false;
   });
   
   // Form Toggles
   jQuery('.toggle').toggles({on: true});
   
   // Minimize Button in Panels
   jQuery('.minimize').click(function(){
      var t = jQuery(this);
      var p = t.closest('.panel');
      if(!jQuery(this).hasClass('maximize')) {
         p.find('.panel-body, .panel-footer').slideUp(200);
         t.addClass('maximize');
         t.html('&plus;');
      } else {
         p.find('.panel-body, .panel-footer').slideDown(200);
         t.removeClass('maximize');
         t.html('&minus;');
      }
      return false;
   });
   
   
   // Add class everytime a mouse pointer hover over it
   jQuery('.nav-bracket > li').hover(function(){
      jQuery(this).addClass('nav-hover');
   }, function(){
      jQuery(this).removeClass('nav-hover');
   });
   
   
   // Menu Toggle
   jQuery('.menutoggle').click(function(){
      
      var body = jQuery('body');
      var bodypos = body.css('position');

      if(bodypos != 'relative') {

         if(!body.hasClass('leftpanel-collapsed')) {
            body.addClass('leftpanel-collapsed');
            jQuery('.nav-bracket ul').attr('style','');

            jQuery(this).addClass('menu-collapsed');

         } else {
            body.removeClass('leftpanel-collapsed');
            jQuery('.nav-bracket li.active ul').css({display: 'block'});

            jQuery(this).removeClass('menu-collapsed');

         }
      } else {

         body.removeClass('leftpanel-collapsed');

         if(body.hasClass('leftpanel-show'))
            body.removeClass('leftpanel-show');
         else
            body.addClass('leftpanel-show');
         
         adjustmainpanelheight();         
      }

   });

   reposition_searchform();

   function reposition_searchform() {
      if(jQuery('.searchform').css('position') == 'relative') {
         jQuery('.searchform').insertBefore('.leftpanelinner .userlogged');
      } else {
         jQuery('.searchform').insertBefore('.header-right');
      }
   }
   
   jQuery('.gritter_message').on('fullyLoaded', function(){
      gritter_tag = $(this);
      addGritterNotification({
         title: gritter_tag.data('title') || null,
         text: gritter_tag.html(),
         class_name: gritter_tag.data('class') || null,
         image: gritter_tag.data('image') || null,
         sticky: gritter_tag.data('sticky') || false,
         time: gritter_tag.data('time') || '5000'
      });
   });

});