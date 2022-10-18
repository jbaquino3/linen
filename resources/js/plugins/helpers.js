export const findIndexByProperty = (items, key, value) => {
    return items.findIndex(item => item[key] == value)
}

export const updateArrayByProperty = (items, key, value, data) => {
    const index = findIndexByProperty(items, key, value)
    items[index] = data
    return items
}