<?php

class BotanicalMappingProjectAdmin extends ModelAdmin
{

    private static $menu_title = 'Projects';

    private static $url_segment = 'botanicalmappingprojects';

    private static $managed_models = array(
        'BotanicalMappingProject'
    );

    private static $menu_icon = '';
}