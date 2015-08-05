/**
 * Created by Developer on 05-08-2015.
 */

LeftMenu = {

    leftMenuID: '#left_menu_accordion',

    init: function(data){
        if(data !== undefined){
            $.each(data, function(index, value){
                if(LeftMenu[index] !== undefined){
                    LeftMenu[index] = value;
                }
            });
        }

        LeftMenu.setAutoFunctions();
        LeftMenu.setHandlers();
    },

    setAutoFunctions: function(){
        LeftMenu.initLeftMenuPlugin();
    },

    setHandlers: function(){

    },


    // Auto functions

    initLeftMenuPlugin: function(){
        $(LeftMenu.leftMenuID).metisMenu();
    }

    // END Auto functions


    // Event handlers

    // END Event handlers


    // Event handlers

    // END Event handlers


    // Public functions

    // END Public functions


    // Private functions

    // END Private functions

};