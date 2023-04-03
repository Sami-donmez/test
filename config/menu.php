<?php
return [
   [
        "name"=>'Dashboard',
        "icon"=>'<i class="fa-lg fa-solid fa-chart-line"></i>',
        "url"=>'/dashboard',
        "permission"=>"",
        "submenu"=>null
    ],
    [
        "name"=>'Pipeline',
        "icon"=>'',
        "url"=>'/salesflow',
        "permission"=>"",
        "submenu"=>null
    ],
    [
        "name"=>'Crm',
        "icon"=>'<i class="fa-lg fa-solid fa-buildings"></i>',
        "url"=>'',
        "permission"=>"",
        "submenu"=>[
            [
                "name"=>'Contacts',
                "url"=>'/contacts'
            ],
            [
                "name"=>'Customers',
                "url"=>'/customers'
            ],
            [
                "name"=>'Leads',
                "url"=>'/leads'
            ]
        ]
    ],

    [
        "name"=>'WorkFlow',
        "icon"=>'',
        "url"=>'/workflow',
        "permission"=>"",
        "submenu"=>null
    ],
    [
        "name"=>'Taken',
        "icon"=>' <i class="fa-lg fas fa-check-square"></i>',
        "url"=>'/tasks',
        "permission"=>"",
        "submenu"=>null
    ],
    [
        "name"=>'ERP',
        "icon"=>'',
        "url"=>'',
        "permission"=>"",
        "submenu"=>[
            [
                'name'=>"Product",
                'url'=>"/products",
            ],
            [
                'name'=>"Supplier",
                'url'=>"/suppliers"
            ]
        ]
    ],
    [
        "name"=>'Gebruikers',
        "icon"=>'<i class="fa-lg fa-solid fa-screen-users"></i>',
        "url"=>'/users',
        "permission"=>"",
        "submenu"=>null
    ],[
        "name"=>'Planning',
        "icon"=>'<i class=" fa-lg fa-solid fa-calendar"></i>',
        "url"=>'/calendar',
        "permission"=>"",
        "submenu"=>null
    ],

    [
        "name"=>'Logout',
        "icon"=>'<i class="fa-lg fa fa-sign-out-alt"></i>',
        "url"=>'/logout',
        "permission"=>"",
        "submenu"=>null
    ],


];
