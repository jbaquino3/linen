import { defineStore } from 'pinia'
import { toRefs, computed, reactive } from 'vue'
import * as stockRoomApi from '@/api/stock_room'

export const useStockRoomStore = defineStore('stock_room', () => {
    const stock_rooms = reactive(stockRoomsObject)
    const dialog = reactive(dialogObject)
    const computed_stock_rooms = computed(() => stock_rooms.data)

    async function fetchStockRooms() {
        stock_rooms.init()
        const res = await stockRoomApi.index()
        res.status ? stock_rooms.success(loadStockRooms(res.data)) : stock_rooms.error(res.data)
    }

    async function updateStockRoom(data, id) {
        dialog.init()
        const res = await stockRoomApi.update(data, id)
        if(res.status) {
            stock_rooms.update(id, loadStockRooms([res.data])[0])
            dialog.success()
        } else {
            dialog.error(res.data)
        }
    }

    async function createStockRoom(data) {
        dialog.init()
        const res = await stockRoomApi.store(data)
        if(res.status) {
            stock_rooms.insert(loadStockRooms([res.data])[0])
            dialog.success()
        } else {
            dialog.error(res.data)
        }
    }

    async function deleteStockRoom(id) {
        stock_rooms.init()
        const res = await stockRoomApi.destroy(id)
        if(res.status) {
            stock_rooms.delete(id)
        } else {
            stock_rooms.error(res.data)
        }
    }
  
    return {
        ...toRefs(stock_rooms),
        ...toRefs(dialog),
        computed_stock_rooms,
        fetchStockRooms,
        updateStockRoom,
        createStockRoom,
        deleteStockRoom
    }
})

const stockRoomsObject = {
    data: [],
    stock_rooms_loading: false,
    stock_rooms_error: null,
    selected_stock_room: null,
    init: function () {
        this.selected_stock_room = null
        this.stock_rooms_loading = true
        this.stock_rooms_error = null
    },
    error: function(err) {
        this.stock_rooms_loading = false
        this.stock_rooms_error = err
    },
    success: function(data) {
        this.stock_rooms_loading = false
        this.data = data
        if(data.length > 0)
            this.selected_stock_room = Object.assign({}, data[0])
    },
    update: function(id, data) {
        this.selected_stock_room = null
        this.success([...updateArrayByProperty(this.data, 'id', id, data)])
    },
    insert: function(data) {
        this.data.unshift(data)
        this.selected_stock_room = null
    },
    delete: function(id) {
        const index = this.data.findIndex(m => m.id == id)
        this.data.splice(index, 1)
        this.stock_rooms_loading = false
    }
}

const dialogObject = {
    stock_room_dialog: false,
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
        this.stock_room_dialog = false
    }
}

function loadStockRooms(data) {
    let items = []
    data.forEach(item => {
        items.push(item)
    })
    return items
}