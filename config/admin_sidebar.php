<?php

return [
    [
        'active' => ['admin.post-catalogues.*'],
        'show' => ['admin.post-catalogues.*'],
        'title' => 'Chuyên mục bài viết',
        'icon' => 'ti ti-category fs-2',
        'permission' => ['viewPostCatalogue', 'createPostCatalogue', 'editPostCatalogue', 'deletePostCatalogue'],
        'children' => [
            [
                'title' => 'Thêm mới',
                'route' => 'admin.post-catalogues.create',
                'icon' => 'ti ti-plus fs-3 me-2',
                'permission' => 'createPostCatalogue',
            ],
            [
                'title' => 'Danh sách',
                'route' => 'admin.post-catalogues.index',
                'icon' => 'ti ti-list fs-3 me-2',
                'permission' => 'viewPostCatalogue',
            ],
        ],
    ],
    [
        'active' => ['admin.posts.*'],
        'show' => ['admin.posts.*'],
        'title' => 'Bài viết',
        'icon' => 'ti ti-news fs-2',
        'permission' => ['viewPost', 'createPost', 'editPost', 'deletePost'],
        'children' => [
            [
                'title' => 'Thêm mới',
                'route' => 'admin.posts.create',
                'icon' => 'ti ti-plus fs-3 me-2',
                'permission' => 'createPost',
            ],
            [
                'title' => 'Danh sách',
                'route' => 'admin.posts.index',
                'icon' => 'ti ti-list fs-3 me-2',
                'permission' => 'viewPost',
            ],
        ],
    ],
    [
        'active' => ['admin.attributes.*'],
        'show' => ['admin.attributes.*'],
        'title' => 'Thuộc tính',
        'icon' => 'ti ti-tags fs-2',
        'permission' => ['viewAttribute', 'createAttribute', 'editAttribute', 'deleteAttribute'],
        'children' => [
            [
                'title' => 'Thêm mới',
                'route' => 'admin.attributes.create',
                'icon' => 'ti ti-plus fs-3 me-2',
                'permission' => 'createAttribute',
            ],
            [
                'title' => 'Danh sách',
                'route' => 'admin.attributes.index',
                'icon' => 'ti ti-list fs-3 me-2',
                'permission' => 'viewAttribute',
            ],
        ],
    ],
    [
        'active' => ['admin.brands.*'],
        'show' => ['admin.brands.*'],
        'title' => 'Thương hiệu',
        'icon' => 'ti ti-award fs-2',
        'permission' => ['viewBrand', 'createBrand', 'editBrand', 'deleteBrand'],
        'children' => [
            [
                'title' => 'Thêm mới',
                'route' => 'admin.brands.create',
                'icon' => 'ti ti-plus fs-3 me-2',
                'permission' => 'createBrand',
            ],
            [
                'title' => 'Danh sách',
                'route' => 'admin.brands.index',
                'icon' => 'ti ti-list fs-3 me-2',
                'permission' => 'viewBrand',
            ],
        ],
    ],
    [
        'active' => ['admin.product-catalogues.*'],
        'show' => ['admin.product-catalogues.*'],
        'title' => 'Danh mục sản phẩm',
        'icon' => 'ti ti-category-2 fs-2',
        'permission' => ['viewProductCatalogue', 'createProductCatalogue', 'editProductCatalogue', 'deleteProductCatalogue'],
        'children' => [
            [
                'title' => 'Thêm mới',
                'route' => 'admin.product-catalogues.create',
                'icon' => 'ti ti-plus fs-3 me-2',
                'permission' => 'createProductCatalogue',
            ],
            [
                'title' => 'Danh sách',
                'route' => 'admin.product-catalogues.index',
                'icon' => 'ti ti-list fs-3 me-2',
                'permission' => 'viewProductCatalogue',
            ],
        ],
    ],
    [
        'active' => ['admin.product-tags.*'],
        'show' => ['admin.product-tags.*'],
        'title' => 'Thẻ sản phẩm',
        'icon' => 'ti ti-tag fs-2',
        'permission' => ['viewProductTag', 'createProductTag', 'editProductTag', 'deleteProductTag'],
        'children' => [
            [
                'title' => 'Thêm mới',
                'route' => 'admin.product-tags.create',
                'icon' => 'ti ti-plus fs-3 me-2',
                'permission' => 'createProductTag',
            ],
            [
                'title' => 'Danh sách',
                'route' => 'admin.product-tags.index',
                'icon' => 'ti ti-list fs-3 me-2',
                'permission' => 'viewProductTag',
            ],
        ],
    ],
    [
        'active' => ['admin.collections.*'],
        'show' => ['admin.collections.*'],
        'title' => 'Bộ sưu tập',
        'icon' => 'ti ti-layout-grid fs-2',
        'permission' => ['viewCollection', 'createCollection', 'editCollection', 'deleteCollection'],
        'children' => [
            [
                'title' => 'Thêm mới',
                'route' => 'admin.collections.create',
                'icon' => 'ti ti-plus fs-3 me-2',
                'permission' => 'createCollection',
            ],
            [
                'title' => 'Danh sách',
                'route' => 'admin.collections.index',
                'icon' => 'ti ti-list fs-3 me-2',
                'permission' => 'viewCollection',
            ],
        ],
    ],
    [
        'active' => ['admin.products.*'],
        'show' => ['admin.products.*'],
        'title' => 'Sản phẩm',
        'icon' => 'ti ti-package fs-2',
        'permission' => ['viewProduct', 'createProduct', 'editProduct', 'deleteProduct'],
        'children' => [
            [
                'title' => 'Thêm mới',
                'route' => 'admin.products.create',
                'icon' => 'ti ti-plus fs-3 me-2',
                'permission' => 'createProduct',
            ],
            [
                'title' => 'Danh sách',
                'route' => 'admin.products.index',
                'icon' => 'ti ti-list fs-3 me-2',
                'permission' => 'viewProduct',
            ],
        ],
    ],
    [
        'active' => ['admin.campaigns.*', 'admin.campaign-segments.*'],
        'show' => ['admin.campaigns.*', 'admin.campaign-segments.*'],
        'title' => 'Marketing',
        'icon' => 'ti ti-speakerphone fs-2',
        'permission' => ['viewCampaign', 'createCampaign', 'editCampaign', 'deleteCampaign'],
        'children' => [
            [
                'title' => 'Chiến dịch',
                'route' => 'admin.campaigns.index',
                'icon' => 'ti ti-list fs-3 me-2',
                'permission' => 'viewCampaign',
            ],
            [
                'title' => 'Phân khúc KH',
                'route' => 'admin.campaign-segments.index',
                'icon' => 'ti ti-users-group fs-3 me-2',
                'permission' => 'viewCampaign',
            ],
        ],
    ],
    [
        'active' => ['admin.sliders.*'],
        'show' => ['admin.sliders.*'],
        'title' => 'Slider',
        'icon' => 'ti ti-photo-scan fs-2',
        'permission' => ['viewSlider', 'createSlider', 'editSlider', 'deleteSlider'],
        'children' => [
            [
                'title' => 'Thêm mới',
                'route' => 'admin.sliders.create',
                'icon' => 'ti ti-plus fs-3 me-2',
                'permission' => 'createSlider',
            ],
            [
                'title' => 'Danh sách',
                'route' => 'admin.sliders.index',
                'icon' => 'ti ti-list fs-3 me-2',
                'permission' => 'viewSlider',
            ],
        ],
    ],
    [
        'active' => ['admin.admins.*'],
        'show' => ['admin.admins.*'],
        'title' => 'Quản trị viên',
        'icon' => 'ti ti-user-code fs-2',
        'permission' => ['viewAdmin', 'createAdmin', 'editAdmin', 'deleteAdmin'],
        'children' => [
            [
                'title' => 'Thêm mới',
                'route' => 'admin.admins.create',
                'icon' => 'ti ti-plus fs-3 me-2',
                'permission' => 'createAdmin',
            ],
            [
                'title' => 'Danh sách',
                'route' => 'admin.admins.index',
                'icon' => 'ti ti-list fs-3 me-2',
                'permission' => 'viewAdmin',
            ],
        ],
    ],
    [
        'active' => ['admin.roles.*'],
        'show' => ['admin.roles.*'],
        'title' => 'Vai trò',
        'icon' => 'ti ti-code fs-2',
        'permission' => ['viewRole', 'createRole', 'editRole', 'deleteRole'],
        'children' => [
            [
                'title' => 'Thêm mới',
                'route' => 'admin.roles.create',
                'icon' => 'ti ti-plus fs-3 me-2',
                'permission' => 'createRole',
            ],
            [
                'title' => 'Danh sách',
                'route' => 'admin.roles.index',
                'icon' => 'ti ti-list fs-3 me-2',
                'permission' => 'viewRole',
            ],
        ],
    ],
    [
        'active' => ['admin.permissions.*'],
        'show' => ['admin.permissions.*'],
        'title' => 'Phân quyền',
        'icon' => 'ti ti-code fs-2',
        'permission' => ['viewPermission', 'createPermission', 'editPermission', 'deletePermission'],
        'children' => [
            [
                'title' => 'Thêm mới',
                'route' => 'admin.permissions.create',
                'icon' => 'ti ti-plus fs-3 me-2',
                'permission' => 'createPermission',
            ],
            [
                'title' => 'Danh sách',
                'route' => 'admin.permissions.index',
                'icon' => 'ti ti-list fs-3 me-2',
                'permission' => 'viewPermission',
            ],
        ],
    ],
    [
        'active' => ['admin.modules.*'],
        'show' => ['admin.modules.*'],
        'title' => 'Module hệ thống',
        'icon' => 'ti ti-code fs-2',
        'permission' => ['viewModule', 'createModule', 'editModule', 'deleteModule'],
        'children' => [
            [
                'title' => 'Thêm mới',
                'route' => 'admin.modules.create',
                'icon' => 'ti ti-plus fs-3 me-2',
                'permission' => 'createModule',
            ],
            [
                'title' => 'Danh sách',
                'route' => 'admin.modules.index',
                'icon' => 'ti ti-list fs-3 me-2',
                'permission' => 'viewModule',
            ],
        ],
    ],
];
