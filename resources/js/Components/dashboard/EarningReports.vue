<script setup>
import { ref } from 'vue';
import { Icon } from '@iconify/vue';

const items = ref([{ title: 'Action' }, { title: 'Another action' }, { title: 'Something else here' }]);

const EarningReportsData = ref([
    {
        title: 'Total Revenue',
        subtitle: 'Monthly earnings',
        icon: 'dollar-minimalistic-bold-duotone',
        color: 'success',
        statuscolor: 'success',
        statustext: '+12.5%'
    },
    {
        title: 'Total Sales',
        subtitle: 'Monthly sales',
        icon: 'cart-3-line-duotone',
        color: 'primary',
        statuscolor: 'success',
        statustext: '+8.2%'
    },
    {
        title: 'Total Orders',
        subtitle: 'Monthly orders',
        icon: 'shopping-bag-line-duotone',
        color: 'warning',
        statuscolor: 'success',
        statustext: '+15.3%'
    },
    {
        title: 'Total Customers',
        subtitle: 'Monthly customers',
        icon: 'users-group-rounded-bold-duotone',
        color: 'error',
        statuscolor: 'success',
        statustext: '+5.7%'
    }
]);
</script>

<template>
    <v-card elevation="10">
        <v-card-item>
            <div class="d-flex justify-space-between align-center mb-mb-0 mb-3">
                <v-card-title class="text-h5 mb-0">Earning Reports</v-card-title>
                <v-menu bottom left>
                    <template v-slot:activator="{ props }">
                        <v-btn icon color="inherit" v-bind="props" flat size="small">
                            <v-icon>mdi-dots-vertical</v-icon>
                        </v-btn>
                    </template>
                    <v-list density="compact" elevation="10">
                        <v-list-item v-for="(item, i) in items" :key="i" :value="i">
                            <v-list-item-title>{{ item.title }}</v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-menu>
            </div>

            <v-row class="mt-4">
                <v-col cols="12" class="d-flex flex-column py-4 border-b" v-for="item in EarningReportsData" :key="item.title">
                    <div class="d-flex justify-space-between ga-3 align-center">
                        <div class="d-flex ga-3 align-center">
                            <div :class="'rounded-pill d-flex justify-center align-center py-2 px-4 bg-light' + item.color">
                                <Icon :icon="'solar:' + item.icon" width="25" height="25" :class="'text-' + item.color" />
                            </div>
                            <div>
                                <h6 class="font-weight-semibold text-body-1">{{ item.title }}</h6>
                                <p class="textSecondary text-12">{{ item.subtitle }}</p>
                            </div>
                        </div>
                        <v-chip :color="item.statuscolor" size="small">
                            <v-icon icon="mdi-arrow-up" height="12" class="me-1"></v-icon> {{ item.statustext }}
                        </v-chip>
                    </div>
                </v-col>
            </v-row>
            <div class="pt-10 pb-2 text-center custom-text-primary">
                <a href="#" class="text-decoration-none text-h6 textPrimary font-weight-medium custom-title">View more markets</a>
            </div>
        </v-card-item>
    </v-card>
</template>
