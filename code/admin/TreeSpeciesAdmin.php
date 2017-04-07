<?php

class TreeSpeciesAdmin extends ModelAdmin
{

    private static $menu_title = 'Tree Species';

    private static $url_segment = 'TreeSpecies';

    private static $managed_models = array(
        'TreeSpecies'
    );

    private static $menu_icon = '';
}