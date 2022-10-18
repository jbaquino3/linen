<template>
    <div>
        <table-filters
            :filterable="filterable"
            :actions="[
                {text: 'Add Raw Material', color: 'primary', emit: 'add', icon: mdiPlus}
            ]"
            v-model="filters"
            @search="s => search=s"
            @reload="reload"
            @add="material_dialog=true"
        ></table-filters>

        <v-alert v-if="materials_error" type="error" text class="mt-2">
            {{materials_error}}
        </v-alert>
        
        <v-card class="mt-2" flat>
            <v-data-table :headers="headers" :items="computed_materials" :search="search" :disabled="materials_loading" :loading="materials_loading">
                <template v-slot:[`item.description`]="{ item }">
                    <table-material-details
                        :unit="item.unit"
                        :description="item.description"
                        :stock-number="item.stock_number"
                        :type="item.type"
                        :unit-cost="item.unit_cost"
                        :storage-name="item.storage.storage_name"
                    ></table-material-details>
                </template>

                <template v-slot:[`item.available`]="{ item }">
                    <available-bar
                        :disabled="!!item.archived_at"
                        disabled_label="ARCHIVED"
                        :available="item.quantity-item.quantity_used"
                        :total="item.quantity"
                        :unit="item.unit"
                    ></available-bar>
                </template>

                <template v-slot:[`item.actions`]="{ item }">
                    <div class="d-flex">
                        <div class="mr-1">
                            <table-edit-button @click="openEdit(item)"></table-edit-button>
                        </div>
                        <div class="mr-1">
                            <table-delete-button :disabled="item.quantity_used>0" @delete="destroy(item)"></table-delete-button>
                        </div>
                    </div>
                </template>
            </v-data-table>
        </v-card>
    </div>
</template>

<script>
    import { onMounted, ref } from 'vue'
    import { useMaterialStore } from '@/stores/material'
    import { storeToRefs } from 'pinia'
    import { mdiPlus } from '@mdi/js'

    export default {
        setup() {
            const materialStore = useMaterialStore()
            const {
                computed_materials,
                materials_loading,
                material_dialog,
                materials_error,
                selected_material,
                filters,
                filterable
            } = storeToRefs(materialStore)
            const search = ref("")

            onMounted(() => {
                reload()
            })

            function reload() {
                materialStore.fetchMaterials()
            }

            function openEdit(item) {
                selected_material.value = Object.assign({}, item)
                material_dialog.value = true
            }

            function destroy(item) {
                materialStore.deleteMaterial(item.stock_number)
            }

            return {
                computed_materials,
                materials_loading,
                materials_error,
                headers,
                search,
                filters,
                filterable,
                openEdit,
                destroy,
                reload,
                ...icons
            }
        },
    }

    const headers = [
        {text: "Description", value: "description"},
        {text: "Available", value: "available"},
        {text: "Actions", value: "actions"},
    ]

    const icons = {
        mdiPlus
    }
</script>