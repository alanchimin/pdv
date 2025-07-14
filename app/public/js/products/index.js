let productList;
$(() => {
    productList = new BaseList({
        entity: 'product',
        containerId: 'product-container',
        columns: [
            { field: 'product_id' },
            { field: 'name' },
            { field: 'category_name' },
            { field: 'symbol' },
            { field: 'unit_price' },
            { field: 'discount' }
        ]
    });
});
