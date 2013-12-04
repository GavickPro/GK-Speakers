/**
 *
 * -------------------------------------------
 * GK Speakers Plugin jQuery file
 * -------------------------------------------
 * 
 **/


// Keydown implementation
jQuery(document).ready(function(){
  jQuery(function () {
      jQuery("#cat_name").keydown(function(){
        that=this;
        setTimeout(function(){
          jQuery(".gk-keydown").text(
            that.value ? that.value : "<empty>"
          )
        },0)   
      });
      
      jQuery("#single_name").keydown(function(){
        that=this;
        setTimeout(function(){
          jQuery(".gk-keydown2").text(
            that.value ? that.value : "<empty>"
          )
        },0)
      }); 
   }
 );
});