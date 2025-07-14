let categoryList;
$(() => {
    categoryList = new BaseList({
        entity: 'category',
        containerId: 'category-container',
        columns: [
            { field: 'category_id' },
            { field: 'name' }
        ],
    });
});
