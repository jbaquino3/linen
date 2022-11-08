import { defineStore } from 'pinia'
import { computed, reactive, watchEffect, toRefs } from 'vue'
import * as transactionApi from '@/api/transaction'
import { updateArrayByProperty } from '@/plugins/helpers'
import useFilters from '../plugins/filter'

const transactionFilters = useFilters()

export const useTransactionStore = defineStore('transaction', () => {
    const transaction = reactive(transactionObject)
    const issued = reactive(issuedObject)
    const filter = getFilterObject()
    const computed_transactions = computed(() => transactionFilters.applyFilter(transaction.data, filter.filterable, filter.filters))

    watchEffect(() => { filter.updateFilters(transaction.data) })

    async function fetchTransactions() {
        transaction.init()
        const res = await transactionApi.index({type: 'ISSUANCE'})
        res.status ? transaction.success(res.data) : transaction.error(res.data)
    }

    async function fetchIssuedProducts() {
        issued.init()
        const res = await transactionApi.issued()
        res.status ? issued.success(res.data) : issued.error(res.data)
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

    async function finalizeTransaction() {
        const res = await transactionApi.finalize(transaction.selected_transaction.id)
        if(res.status) {
            transaction.selected_transaction = res.data
            transaction.update(transaction.selected_transaction.id, res.data)
        } else {
            transaction.error(res.data)
        }
        return res
    }

    async function createTransaction(data) {
        transaction.init()
        const res = await transactionApi.store(data)
        if(res.status) {
            transaction.insert(res.data)
            return res.data
        } else {
            transaction.error(res.data)
            return null
        }
    }

    async function addTransactionItem(data) {
        if(!transaction.selected_transaction.id) {
            transaction.selected_transaction.type = "ISSUANCE"
            transaction.selected_transaction = await createTransaction(transaction.selected_transaction)
        }


        if(transaction.selected_transaction.id) {
            const res = await transactionApi.addItem(data, transaction.selected_transaction.id)
            if(res.status) {
                transaction.selected_transaction.items.push(res.data)
                transaction.transaction_loading = false
                return res.data
            } else {
                transaction.error(res.data)
                return null
            }
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
        ...toRefs(issued),
        computed_transactions,
        fetchTransactions,
        updateTransaction,
        createTransaction,
        deleteTransaction,
        addTransactionItem,
        finalizeTransaction,
        fetchIssuedProducts
    }
})

const transactionObject = {
    data: [],
    transaction_loading: false,
    transaction_error: null,
    selected_transaction: { items: [] },
    init: function () {
        this.selected_transaction = { items: [] }
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
        this.success([...updateArrayByProperty(this.data, 'id', id, data)])
    },
    insert: function(data) {
        const item = this.data.find(i => i.id == data.id)
        if(item) {
            this.update(data.id, data)
        } else {
            this.data.unshift(data)
            this.selected_transaction = { items: [] }
        }
    },
    delete: function(id) {
        const index = this.data.findIndex(m => m.id == id)
        this.data.splice(index, 1)
        this.transaction_loading = false
    }
}

const issuedObject = {
    issued_products: [],
    issued_loading: false,
    issued_error: null,
    init: function () {
        this.issued_loading = true
        this.issued_error = null
    },
    error: function(err) {
        this.issued_loading = false
        this.issued_error = err
    },
    success: function(data) {
        this.issued_loading = false
        this.issued_products = data
    },
    update: function(id, data) {
        this.success([...updateArrayByProperty(this.issued_products, 'id', id, data)])
    },
    insert: function(data) {
        const item = this.issued_products.find(i => i.id == data.id)
        if(item) {
            this.update(data.id, data)
        } else {
            this.issued_products.unshift(data)
        }
    }
}

function getFilterObject() {
    const ownFilterObject = Object.assign({}, transactionFilters.filtersObject)
    ownFilterObject.addFilterable({text: 'Type', value: 'type', type: 'distinct'})
    return reactive(Object.assign({}, ownFilterObject))
}