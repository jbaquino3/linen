import { defineStore } from 'pinia'
import { computed, reactive, watchEffect, toRefs } from 'vue'
import * as transactionApi from '@/api/transaction'
import { updateArrayByProperty } from '@/plugins/helpers'
import useFilters from '../plugins/filter'

const transactionFilters = useFilters()

export const useTransactionStore = defineStore('transaction', () => {
    const transaction = reactive(transactionObject)
    const filter = getFilterObject()
    const computed_transactions = computed(() => transactionFilters.applyFilter(transaction.data, filter.filterable, filter.filters))

    watchEffect(() => { filter.updateFilters(transaction.data) })

    async function fetchTransactions() {
        transaction.init()
        const res = await transactionApi.index()
        res.status ? transaction.success(res.data) : transaction.error(res.data)
    }

    async function updateTransaction(data, id) {
        transaction.init()
        const res = await transactionApi.update(data, id)
        if(res.status) {
            transaction.update(id, res.data)
        } else {
            transaction.error(res.data)
        }
    }

    async function createTransaction(data) {
        transaction.init()
        const res = await transactionApi.store(data)
        if(res.status) {
            transaction.insert(res.data)
        } else {
            transaction.error(res.data)
        }
    }

    async function deleteTransaction(id) {
        transaction.init()
        const res = await transactionApi.destroy(id)
        if(res.status) {
            transaction.delete(id)
        } else {
            transaction.error(res.data)
        }
    }
  
    return {
        ...toRefs(transaction),
        ...toRefs(filter),
        computed_transactions,
        fetchTransactions,
        updateTransaction,
        createTransaction,
        deleteTransaction
    }
})

const transactionObject = {
    data: [],
    transaction_loading: false,
    transaction_error: null,
    selected_transaction: null,
    init: function () {
        this.selected_transaction = null
        this.transaction_loading = true
        this.transaction_error = null
    },
    error: function(err) {
        this.transaction_loading = false
        this.transaction_error = err
    },
    success: function(data) {
        this.transaction_loading = false
        this.data = data
    },
    update: function(id, data) {
        this.selected_transaction = null
        this.success([...updateArrayByProperty(this.data, 'id', id, data)])
    },
    insert: function(data) {
        this.data.unshift(data)
        this.selected_transaction = null
    },
    delete: function(id) {
        const index = this.data.findIndex(m => m.id == id)
        this.data.splice(index, 1)
        this.transaction_loading = false
    }
}

function getFilterObject() {
    const ownFilterObject = Object.assign({}, transactionFilters.filtersObject)
    ownFilterObject.addFilterable({text: 'Type', value: 'type', type: 'distinct'})
    return reactive(Object.assign({}, ownFilterObject))
}