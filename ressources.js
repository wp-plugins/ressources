var ressources_refresh_delay = [];
function resources_refresh_content(target){
    data_name = jQuery(target).data('id');
    ressources_refresh_delay[data_name] = jQuery(target).data('refresh');
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
        async:true,
        success:function(html){
            target.html(html);
            data_name = jQuery(target).data('id');
            if(ressources_refresh_delay[data_name]>0){
                setTimeout(function(){resources_refresh_content(target);},ressources_refresh_delay[data_name]*1000);
            }
        },
        error:function(){
            if(ressources_refresh_delay[data_name]>0){
                setTimeout(function(){resources_refresh_content(target);},ressources_refresh_delay[data_name]*10000);
            }
        }
    });
}

jQuery(document).ready(function(){
    jQuery('.ressources-widget-content').each(function(){
        resources_refresh_content(jQuery(this));
    });
});