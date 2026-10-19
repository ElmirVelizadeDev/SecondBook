<script setup>
import UsersIndex from './UsersIndex.vue';
import UserEditor from './UserEditor.vue';
import UserDetails from './UserDetails.vue';
const props=defineProps({mode:String,indexUrl:String,createUrl:String,storeUrl:String,updateUrl:String,csrf:String,payload:String,errors:String,old:String});
</script>
<template><UsersIndex v-if="mode==='index'" v-bind="props"/><UserEditor v-else-if="mode==='create'||mode==='edit'" v-bind="props"/><UserDetails v-else v-bind="props"/></template>
