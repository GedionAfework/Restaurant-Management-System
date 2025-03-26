<template>
  <AdminLayout>
    <h1 class="text-2xl font-bold">Manage Employees</h1>
    <ul>
      <li v-for="employee in employees" :key="employee.id">
        {{ employee.name }} - {{ employee.email }}
      </li>
    </ul>
  </AdminLayout>
</template>

<script>
import AdminLayout from './Layout.vue';
export default {
  components : { AdminLayout },
  props: {
    employees: Array,  // Data passed from the controller
  },
};
</script>
