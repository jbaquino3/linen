export function applyFilter (items, filterable, filters) {
    for(var k in filters) {
        if(filterable.find(f => f.value == k).type == "distinct") {
            if(filters[k] && filters[k].length > 0) {
                items = items.filter(item => {
                    return filters[k].includes(item[k])
                })
            }
        } else {
            if(filters[k] === true) {
                items = items.filter(item => {
                    return item[k]
                })
            } else if(filters[k] === false) {
                items = items.filter(item => {
                    return !item[k]
                })
            }
        }
    }

    return items
}