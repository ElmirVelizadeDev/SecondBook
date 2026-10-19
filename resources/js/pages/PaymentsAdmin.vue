<script setup>
import PaymentsIndex from './PaymentsIndex.vue';
import PaymentEditor from './PaymentEditor.vue';
const props=defineProps({mode:String,indexUrl:String,createUrl:String,storeUrl:String,updateUrl:String,csrf:String,payload:String,errors:String,old:String});
</script>
<template><PaymentsIndex v-if="mode==='index'" v-bind="props"/><PaymentEditor v-else v-bind="props"/></template>
