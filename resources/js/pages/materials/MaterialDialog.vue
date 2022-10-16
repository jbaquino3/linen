<template>
    <div>
        <v-dialog v-model="material_dialog" persistent max-width="800">
            <v-card :loading="dialog_loading">
                <v-card-title>
                    <v-container class="d-flex" fluid>
                        {{selected_material ? "Update Material" : "Create Material"}}
                        <v-chip label v-if="selected_material" class="ml-2 green" dark>
                            Stock Number: {{material.stock_number}}
                        </v-chip>
                        <v-spacer></v-spacer>
                        <v-icon @click="closeDialog">{{mdiClose}}</v-icon>
                    </v-container>
                </v-card-title>

                <v-card-text>
                    <v-container fluid>
                        <v-alert v-if="dialog_error" type="error" text class="mb-2">
                            {{dialog_error}}
                        </v-alert>

                        <v-row>
                            <v-col cols="12" sm="4">
                                <v-select
                                    label="Type"
                                    v-model="material.type"
                                    :items="['RAW', 'READY-MADE']"
                                ></v-select>
                            </v-col>

                            <v-col cols="12" sm="4">
                                <v-menu v-model="date_menu" :close-on-content-click="false" min-width="auto">
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field v-model="material.received_at" label="Received Date" readonly v-bind="attrs" v-on="on"></v-text-field>
                                    </template>
                                    <v-date-picker v-model="material.received_at" no-title @input="date_menu = false"></v-date-picker>
                                </v-menu>
                            </v-col>

                            <v-col cols="12" sm="4">
                                <v-radio-group v-model="material.archived" row>
                                    <v-radio
                                        label="Available"
                                        :value="false"
                                    ></v-radio>
                                    <v-radio
                                        label="Archived"
                                        :value="true"
                                    ></v-radio>
                                </v-radio-group>
                            </v-col>

                            <v-col cols="12" sm="4">
                                <v-select
                                    label="Unit"
                                    v-model="material.unit"
                                    :items="['PIECE', 'SPOOL', 'YARD', 'ROLL', 'SACK/BAG']"
                                ></v-select>
                            </v-col>

                            <v-col cols="12" sm="4">
                                <v-text-field
                                    label="Total Quantity"
                                    v-model="material.quantity"
                                    type="number"
                                ></v-text-field>
                            </v-col>

                            <v-col cols="12" sm="4">
                                <v-text-field
                                    label="Unit Cost"
                                    v-model="material.unit_cost"
                                    type="number"
                                ></v-text-field>
                            </v-col>

                            <v-col cols="12" sm="8">
                                <v-select
                                    label="Storage"
                                    v-model="material.storage_id"
                                    :items="storage_select_items"
                                ></v-select>
                            </v-col>
                            
                            <v-col cols="12" sm="4">
                                <div class="d-flex flex-column">
                                    <div class="caption">Used: {{material.quantity_used ? material.quantity_used : 0}}</div>

                                    <available-bar
                                        :disabled="!!material.archived"
                                        disabled_label="ARCHIVED"
                                        :available="parseFloat(material.quantity)-(material.quantity_used ? material.quantity_used : 0)"
                                        :total="parseFloat(material.quantity)"
                                        :unit="material.unit"
                                    ></available-bar>
                                </div>
                                
                            </v-col>

                            <v-col cols="12">
                                <v-textarea
                                    label="Description"
                                    v-model="material.description"
                                ></v-textarea>
                            </v-col>
                        </v-row>

                        <v-btn dark color="primary" @click="save">
                            Save
                        </v-btn>
                    </v-container>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    import { useMaterialStore } from '@/stores/material'
    import { useStorageStore } from '@/stores/storage'
    import { storeToRefs } from 'pinia'
    import { mdiClose } from '@mdi/js'
    import { ref, watch } from 'vue'

    export default {
        setup() {
            const materialStore = useMaterialStore()
            const { material_dialog, selected_material } = storeToRefs(materialStore)
            const { storage_select_items, dialog_loading, dialog_error } = storeToRefs(useStorageStore())
            const material = ref({})
            const date_menu = ref(false)

            function closeDialog() {
                material_dialog.value = false
                selected_material.value = null
            }

            function save() {
                if(selected_material.value) {
                    materialStore.updateMaterial(material.value, material.value.stock_number)
                }
            }

            watch(selected_material, (currentValue) => {
                material.value = currentValue ? currentValue : {}
                material.value.archived = !!material.value.archived_at
            })

            return {
                material_dialog,
                material,
                dialog_loading,
                dialog_error,
                selected_material,
                date_menu,
                storage_select_items,
                closeDialog,
                save,
                ...icons
            }
        },
    }

    const icons = {
        mdiClose
    }
</script>