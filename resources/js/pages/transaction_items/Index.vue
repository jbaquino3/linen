<template>
    <div>
        <actions></actions>

        <div class="d-flex">
            <div>
                <issuance-form></issuance-form>
            </div>
            <div ref="printable">
                <items></items>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        setup() {
            
        },

        components: {
            IssuanceForm: () => import('./IssuanceForm'),
            ReturnForm: () => import('./ReturnForm'),
            Actions: () => import('./Actions'),
            Items: () => import('./Items')
        }
    }
</script>