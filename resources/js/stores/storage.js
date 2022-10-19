import { defineStore } from 'pinia'
import { toRefs, computed, reactive } from 'vue'
import * as storageApi from '@/api/storage'
import { useStockRoomStore } from '@/stores/stock_room'

export const useStorageStore = defineStore('storage', () => {
    const stockRoomStore = useStockRoomStore()
    const storages = reactive(storagesObject)
    const dialog = reactive(dialogObject)
    const computed_storages = computed(() => storages.data.filter(i => stockRoomStore.selected_stock_room && i.stock_room_id == stockRoomStore.selected_stock_room.id))

    const storage_select_items = computed(() => {
        if(storages.data.length == 0) {
            fetchStorages()
        }

        let items = []
        storages.data.forEach(stg => {
            items.push({
                text: stg.storage_name + " - " + stg.name,
                value: stg.id
            })
        })
        return items
    })

    async function fetchStorages() {
        storages.init()
        const res = await storageApi.index()
        res.status ? storages.success(loadStorages(res.data)) : storages.error(res.data)
    }

    async function updateStorage(data, id) {
        dialog.init()
        const res = await storageApi.update(data, id)
        if(res.status) {
            storages.update(id, loadStorages([res.data])[0])
            dialog.success()
        } else {
            dialog.error(res.data)
        }
    }

    async function createStorage(data) {
        dialog.init()
        const res = await storageApi.store(data)
        if(res.status) {
            storages.insert(loadStorages([res.data])[0])
            dialog.success()
        } else {
            dialog.error(res.data)
        }
    }

    async function deleteStorage(id) {
        storages.init()
        const res = await storageApi.destroy(id)
        if(res.status) {
            storages.delete(id)
        } else {
            storages.error(res.data)
        }
    }
  
    return {
        ...toRefs(storages),
        ...toRefs(dialog),
        computed_storages,
        storage_select_items,
        fetchStorages,
        updateStorage,
        createStorage,
        deleteStorage
    }
})

const storagesObject = {
    data: [],
    storages_loading: false,
    storages_error: null,
    selected_storage: null,
    init: function () {
        this.selected_storage = null
        this.storages_loading = true
        this.storages_error = null
    },
    error: function(err) {
        this.storages_loading = false
        this.storages_error = err
    },
    success: function(data) {
        this.storages_loading = false
        this.data = data
    },
    update: function(id, data) {
        this.selected_storage = null
        this.success([...updateArrayByProperty(this.data, 'id', id, data)])
    },
    insert: function(data) {
        this.data.unshift(data)
        this.selected_storage = null
    },
    delete: function(id) {
        const index = this.data.findIndex(m => m.id == id)
        this.data.splice(index, 1)
        this.storages_loading = false
    }
}

const dialogObject = {
    storage_dialog: false,
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
        this.storage_dialog = false
    }
}

function loadStorages(data) {
    let items = []
    data.forEach(item => {
        items.push(item)
    })
    return items
}