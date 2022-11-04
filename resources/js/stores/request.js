import { defineStore } from 'pinia'
import { computed, reactive, watchEffect, toRefs } from 'vue'
import * as requestApi from '@/api/request'
import { updateArrayByProperty } from '@/plugins/helpers'
import useFilters from '../plugins/filter'

const requestFilters = useFilters()

export const useRequestStore = defineStore('request', () => {
    const requests = reactive(requestsObject)
    const dialog = reactive(dialogObject)
    const remark = reactive(remarkObject)
    const filter = getFilterObject()
    const computed_requests = computed(() => requestFilters.applyFilter(requests.data, filter.filterable, filter.filters))

    watchEffect(() => { filter.updateFilters(requests.data) })

    async function fetchRequests() {
        requests.init()
        const res = await requestApi.index()
        res.status ? requests.success(res.data) : requests.error(res.data)
    }

    async function updateRequest(data, id) {
        dialog.init()
        const res = await requestApi.update(data, id)
        if(res.status) {
            requests.update(id, res.data)
            dialog.success()
        } else {
            dialog.error(res.data)
        }
    }

    async function createRequest(data) {
        dialog.init()
        const res = await requestApi.store(data)
        if(res.status) {
            requests.insert(res.data)
            dialog.success()
        } else {
            dialog.error(res.data)
        }
    }

    async function createRemark(data) {
        remark.init()
        const res = await requestApi.createRemark(data)
        if(res.status) {
            fetchRequests()
            remark.success()
        } else {
            remark.error(res.data)
        }
        return res
    }

    async function deleteRequest(id) {
        requests.init()
        const res = await requestApi.destroy(id)
        if(res.status) {
            requests.delete(id)
        } else {
            requests.error(res.data)
        }
    }
  
    return {
        ...toRefs(requests),
        ...toRefs(dialog),
        ...toRefs(remark),
        ...toRefs(filter),
        computed_requests,
        fetchRequests,
        updateRequest,
        createRequest,
        deleteRequest,
        createRemark
    }
})

const requestsObject = {
    data: [],
    requests_loading: false,
    requests_error: null,
    selected_request: null,
    init: function () {
        this.selected_request = null
        this.requests_loading = true
        this.requests_error = null
    },
    error: function(err) {
        this.requests_loading = false
        this.requests_error = err
    },
    success: function(data) {
        for(let i=0; i<data.length; i++) {
            if(data[i].cancelled_at) {
                data[i].status = "CANCELLED"
            } else if(data[i].issued_at) {
                data[i].status = "ISSUED"
            } else if(data[i].prepared_at) {
                data[i].status = "READY FOR PICKUP"
            } else if(data[i].processed_at) {
                data[i].status = "PROCESSING"
            } else {
                data[i].status = "PENDING"
            }
        }

        data.sort((x, y) => {
            if(
                x.status == "PENDING" ||
                (x.status == "PROCESSING" && (y.status == "READY FOR PICKUP" || y.status == "ISSUED" || y.status == "CANCELLED")) ||
                (x.status == "READY FOR PICKUP" && (y.status == "ISSUED" || y.status == "CANCELLED")) ||
                (x.status == "ISSUED" && y.status == "CANCELLED")
            ) {
                return -1
            } else if (x.status == y.status) {
                return x.requested_at < y.requested_at
            } else {
                return 1
            }
        })

        this.requests_loading = false
        this.data = data
    },
    update: function(id, data) {
        this.selected_request = null
        this.success([...updateArrayByProperty(this.data, 'id', id, data)])
    },
    insert: function(data) {
        data.status = "PENDING"
        this.data.unshift(data)
        this.selected_request = null
    },
    delete: function(id) {
        const index = this.data.findIndex(m => m.id == id)
        this.data.splice(index, 1)
        this.requests_loading = false
    }
}

const dialogObject = {
    request_dialog: false,
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
        this.request_dialog = false
    }
}

const remarkObject = {
    remark_dialog: false,
    remark_loading: false,
    remark_error: null,
    init: function () {
        this.remark_loading = true
        this.remark_error = null
    },
    error: function(err) {
        this.remark_loading = false
        this.remark_error = err
    },
    success: function() {
        this.remark_loading = false
        this.remark_dialog = false
    }
}

function getFilterObject() {
    const ownFilterObject = Object.assign({}, requestFilters.filtersObject)
    ownFilterObject.addFilterable({text: 'Ward/Office', value: 'location_name', type: 'distinct'})
    ownFilterObject.addFilterable({text: 'Status', value: 'status', type: 'distinct'})
    return reactive(Object.assign({}, ownFilterObject))
}