import { defineStore } from 'pinia'
import { computed, reactive, watchEffect, toRefs } from 'vue'
import * as locationApi from '@/api/location'
import { updateArrayByProperty } from '@/plugins/helpers'
import useFilters from '../plugins/filter'

const locationFilters = useFilters()

export const useLocationStore = defineStore('location', () => {
    const locations = reactive(locationsObject)
    const dialog = reactive(dialogObject)
    const filter = getFilterObject()
    const computed_locations = computed(() => locationFilters.applyFilter(locations.data, filter.filterable, filter.filters))

    const location_select_items = computed(() => {
        if(locations.data.length == 0) {
            fetchLocations()
        }

        let items = []
        locations.data.forEach(stg => {
            items.push({
                text: stg.name + " (" + stg.type + ")",
                value: stg.id
            })
        })
        return items
    })

    watchEffect(() => { filter.updateFilters(locations.data) })

    async function fetchLocations() {
        locations.init()
        const res = await locationApi.index()
        res.status ? locations.success(res.data) : locations.error(res.data)
    }

    async function updateLocation(data, id) {
        dialog.init()
        const res = await locationApi.update(data, id)
        if(res.status) {
            locations.update(id, res.data)
            dialog.success()
        } else {
            dialog.error(res.data)
        }
    }

    async function createLocation(data) {
        dialog.init()
        const res = await locationApi.store(data)
        if(res.status) {
            locations.insert(res.data)
            dialog.success()
        } else {
            dialog.error(res.data)
        }
    }

    async function deleteLocation(id) {
        locations.init()
        const res = await locationApi.destroy(id)
        if(res.status) {
            locations.delete(id)
        } else {
            locations.error(res.data)
        }
    }
  
    return {
        ...toRefs(locations),
        ...toRefs(dialog),
        ...toRefs(filter),
        computed_locations,
        location_select_items,
        fetchLocations,
        updateLocation,
        createLocation,
        deleteLocation
    }
})

const locationsObject = {
    data: [],
    locations_loading: false,
    locations_error: null,
    selected_location: null,
    init: function () {
        this.selected_location = null
        this.locations_loading = true
        this.locations_error = null
    },
    error: function(err) {
        this.locations_loading = false
        this.locations_error = err
    },
    success: function(data) {
        this.locations_loading = false
        this.data = data
    },
    update: function(id, data) {
        this.selected_location = null
        this.success([...updateArrayByProperty(this.data, 'id', id, data)])
    },
    insert: function(data) {
        this.data.unshift(data)
        this.selected_location = null
    },
    delete: function(id) {
        const index = this.data.findIndex(m => m.id == id)
        this.data.splice(index, 1)
        this.locations_loading = false
    }
}

const dialogObject = {
    location_dialog: false,
    dialog_loading: false,
    dialog_error: null,
    init: function () {
        this.dialog_loading = true
        this.dialog_error = null
    },
    error: function(err) {
        this.dialog_loading = false
        this.dialog_error = err
    },
    success: function() {
        this.dialog_loading = false
        this.location_dialog = false
    }
}

function getFilterObject() {
    const ownFilterObject = Object.assign({}, locationFilters.filtersObject)
    ownFilterObject.addFilterable({text: 'Type', value: 'type', type: 'distinct'})
    ownFilterObject.addFilterable({text: 'Is Active', value: 'transaction_count', type: 'boolean'})
    return reactive(Object.assign({}, ownFilterObject))
}