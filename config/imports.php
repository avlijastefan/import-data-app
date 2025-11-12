<?php

return [

    'types' => [

        // ================================
        // 1. ORDERS
        // ================================
        'orders' => [
            'label'               => 'Import Orders',
            'permission_required' => 'import-orders',
            'files'               => [
                'main' => [ 
                    'label'           => 'Orders File',
                    'model'           => \App\Models\Order::class,
                    'update_or_create' => ['so_num', 'sku'],
                    'required_headers' => [
                        'order_date', 'channel', 'sku', 'origin', 'so_num',
                        'cost', 'shipping_cost', 'total_price'
                    ],
                    'headers_to_db' => [
                        'order_date' => [
                            'label'       => 'Order Date',
                            'type'        => 'date',
                            'validation'  => ['required'],
                        ],
                        'channel' => [
                            'label'       => 'Channel',
                            'type'        => 'string',
                            'validation'  => ['required', 'in:PT,Amazon'],
                        ],
                        'sku' => [
                            'label'       => 'SKU',
                            'type'        => 'string',
                            'validation'  => ['required', 'exists:products,sku'],
                        ],
                        'item_description' => [
                            'label'       => 'Item Description',
                            'type'        => 'string',
                            'validation'  => ['nullable'],
                        ],
                        'origin' => [
                            'label'       => 'Origin',
                            'type'        => 'string',
                            'validation'  => ['required'],
                        ],
                        'so_num' => [
                            'label'       => 'SO#',
                            'type'        => 'string',
                            'validation'  => ['required'],
                        ],
                        'cost' => [
                            'label'       => 'Cost',
                            'type'        => 'double',
                            'validation'  => ['required', 'numeric'],
                        ],
                        'shipping_cost' => [
                            'label'       => 'Shipping Cost',
                            'type'        => 'double',
                            'validation'  => ['required', 'numeric'],
                        ],
                        'total_price' => [
                            'label'       => 'Total Price',
                            'type'        => 'double',
                            'validation'  => ['required', 'numeric'],
                        ],
                    ],
                ],
            ],
        ],

        // ================================
        // 2. PRODUCTS
        // ================================
        'products' => [
            'label'               => 'Import Products',
            'permission_required' => 'import-products',
            'files'               => [
                'main' => [
                    'label'           => 'Products File',
                    'model'           => \App\Models\Product::class,
                    'update_or_create' => ['sku'],
                    'required_headers' => ['sku', 'name', 'price'],
                    'headers_to_db' => [
                        'sku' => [
                            'label'       => 'SKU',
                            'type'        => 'string',
                            'validation'  => ['required', 'unique:products,sku'],
                        ],
                        'name' => [
                            'label'       => 'Product Name',
                            'type'        => 'string',
                            'validation'  => ['required'],
                        ],
                        'price' => [
                            'label'       => 'Price',
                            'type'        => 'double',
                            'validation'  => ['required', 'numeric'],
                        ],
                    ],
                ],
            ],
        ],

        // ================================
        // 3. CUSTOMERS
        // ================================
        'customers' => [
            'label'               => 'Import Customers',
            'permission_required' => 'import-customers',
            'files'               => [
                'main' => [ 
                    'label'           => 'Basic Info',
                    'model'           => \App\Models\Customer::class,
                    'update_or_create' => ['customer_id'],
                    'required_headers' => ['customer_id', 'name'],
                    'headers_to_db' => [
                        'customer_id' => [
                            'label'       => 'Customer ID',
                            'type'        => 'integer',
                            'validation'  => ['required', 'unique:customers,customer_id'],
                        ],
                        'name' => [
                            'label'       => 'Name',
                            'type'        => 'string',
                            'validation'  => ['required'],
                        ],
                        'email' => [
                            'label'       => 'Email',
                            'type'        => 'string',
                            'validation'  => ['nullable'],
                        ],
                        'phone' => [
                            'label'       => 'Phone',
                            'type'        => 'string',
                            'validation'  => ['nullable'],
                        ],
                    ],
                ],
            ],
        ],

    ],

];