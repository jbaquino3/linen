

export default function useFilters() {
    const filtersObject = {
        filterable: [],
        filters: {},
        updateFilters: function(items) {
            this.filterable.forEach(f => {
                f.items = getOptions(items, f.value, f.type)
            })
        },
        addFilterable: function (filterable) {
            this.filterable.push(filterable)
            this.filters[filterable.value] = filterable.type == 'distinct' ? [] : null
        }
    }
    
    function applyFilter (items, filterable, filters) {
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
    
    function getOptions(items, value, type) {
        if(type == "boolean") {
            return [
                {text: 'Yes', value: true},
                {text: 'No', value: false}
            ]
        } else {
            var temp = Object.assign([], items).map(c => c[value]).filter(item => item)
            return [...new Set(temp)]
        }
    }

    return {
        filtersObject, applyFilter
    }
}