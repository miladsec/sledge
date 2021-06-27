<?php
return [
    'validation' => [
        'required' => 'وارد کردن این فیلد  اجباری است'
    ],
    'index' => [
        'serverSideProcess' => true,
        'addLinkText' => 'افزودن',
        'addLinkIcon' => 'bx bx-plus',
        'dropdown' => false,
        'actionColumnName' => 'عملیات'
    ],
    'create' => [
        'defaultCol' => 'col-md-6'
    ],
    'alert' =>[
        'success' => 'ok :)',
        'danger' => 'Nok :('
    ],
    'acl' => [
        'status' => true
    ],

    'columnAction' => [
        'route' => '<a class="dropdown-item *1" href="*2">*3 *4</a>',
        'static' => '<div class="dropdown">
                     <span class="bx bx-dots-vertical-rounded font-medium-3 dropdown-toggle nav-hide-arrow cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></span>
                     <div class="dropdown-menu">*1</div>
                     </div>'
    ],

    'route' => [
        'defaultRoute' => 'home',
        'namePrefix' => [
            'index' => 'لیست',
            'create' => 'افزودن',
            'edit' => 'ویرایش',
        ]
    ],
];
