<script setup>
import { ref } from 'vue';

const items = ref([{ title: 'Action' }, { title: 'Another action' }, { title: 'Something else here' }]);

const ProductTableData = ref([
    {
        name: 'Product A',
        img: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=60&q=80',
        payment: '$1,234',
        paymentstatus: 'Paid',
        process: 75,
        processcolor: 'success',
        statuscolor: 'success',
        statustext: 'Active'
    },
    {
        name: 'Product B',
        img: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=60&q=80',
        payment: '$987',
        paymentstatus: 'Pending',
        process: 45,
        processcolor: 'warning',
        statuscolor: 'warning',
        statustext: 'Pending'
    },
    {
        name: 'Product C',
        img: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=60&q=80',
        payment: '$876',
        paymentstatus: 'Paid',
        process: 90,
        processcolor: 'success',
        statuscolor: 'success',
        statustext: 'Active'
    }
]);
</script>
<template>
    <v-card elevation="10">
        <v-card-item class="px-0">
            <div class="px-6">
                <v-card-title class="text-h5 mb-1">Popular Products</v-card-title>
                <v-card-subtitle class="text-body-1">Total 9k Visitors</v-card-subtitle>
            </div>
            <div style="height: 450px; overflow-y: auto;">
                <v-table class="revenue-table border-table">
                    <template v-slot:default>
                        <thead>
                            <tr>
                                <th class="text-h6 font-weight-semibold text-no-wrap">Products</th>
                                <th class="text-h6 font-weight-semibold text-no-wrap">Payment</th>
                                <th class="text-h6 font-weight-semibold text-no-wrap">Status</th>
                                <th class="text-h6 font-weight-semibold text-no-wrap"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in ProductTableData" :key="item.name" class="month-item">
                                <td>
                                    <div class="d-flex align-center overflow-hidden">
                                        <img :src="item.img" alt="user" width="60" class="rounded-md" />
                                        <div class="mx-4" style="min-width: 250px">
                                            <h6 class="text-body-1 font-weight-semibold truncate-2">
                                                {{ item.name }}
                                            </h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <h5 class="text-h6 font-weight-semibold text-no-wrap mb-1 text-no-wrap">
                                        {{ item.payment }}<span class="text-muted">/ 499</span>
                                    </h5>
                                    <div class="text-body-1 font-weight-medium text-muted mb-3 text-no-wrap">
                                        {{ item.paymentstatus }}
                                    </div>
                                    <div>
                                        <v-progress-linear
                                            :color="item.processcolor"
                                            rounded="sm"
                                            :model-value="item.process"
                                            bg-color="light"
                                        ></v-progress-linear>
                                    </div>
                                </td>
                                <td>
                                    <v-chip size="x-small" :color="item.statuscolor">{{ item.statustext }}</v-chip>
                                </td>
                                <td class="text-right">
                                    <v-btn size="30" icon variant="flat" class="grey100">
                                        <v-avatar size="22">
                                            <v-icon>mdi-dots-vertical</v-icon>
                                        </v-avatar>
                                    </v-btn>
                                </td>
                            </tr>
                        </tbody>
                    </template>
                </v-table>
            </div>
        </v-card-item>
    </v-card>
</template>

<style scoped>
.revenue-table {
    width: 100%;
}
</style>
