function resources_refresh_content(target){
    data_name = jQuery(target).data('id');
    if(jQuery('#ressources_'+data_name).hasClass('closed')){
        setTimeout(function(){resources_refresh_content(target);},2000);
        return;
    }
    jQuery.ajax(ajaxurl,{
        data:{
            action:'ressources_widget_content',
            datas:data_name
        },
        datasType:'html',
        success:function(html){
            target.html(html);
            setTimeout(function(){resources_refresh_content(target);},2000);
        },
        error:function(){
            setTimeout(function(){resources_refresh_content(target);},60000);
        }
    });
}

jQuery(document).ready(function(){
    jQuery('.ressources-widget-content').each(function(){
        resources_refresh_content(jQuery(this));
    });
});