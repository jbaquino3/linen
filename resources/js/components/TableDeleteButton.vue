<template>
        <v-tooltip top>
            <template v-slot:activator="{ on, attrs }">
                <v-btn v-bind="attrs" v-on="on" small elevation="0" color="red" dark @click="del">
                    <v-icon>
                        {{mdiDelete}}
                    </v-icon>
                </v-btn>
            </template>
            <span>Delete</span>
        </v-tooltip>
</template>

<script>
    import { mdiDelete } from '@mdi/js'
    import useAlerts from '@/utils/alerts'

    export default {
        setup(props, {emit}) {
            const alerts = useAlerts()

            function del() {
                alerts.alertConfirmation({
                    title: "Delete Record?",
                    text: "Are your sure you want to delete this record?",
                }).then( (swal) => {
                    if(swal.isConfirmed) {
                        emit("delete")
                    }
                })
            }

            return {
                del,
                ...icons
            }
        },
    }

    const icons = {
        mdiDelete
    }
</script>