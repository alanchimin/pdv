let unitList;
$(() => {
    unitList = new BaseList({
        entity: 'unit',
        containerId: 'unit-container',
        columns: [
            { field: 'unit_id' },
            { field: 'name' },
            { field: 'symbol' }
        ],
    });
});
