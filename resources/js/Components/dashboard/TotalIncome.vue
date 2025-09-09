<script setup>
import { Icon } from '@iconify/vue';
import { ref, computed } from 'vue';

/* Chart */
const chartOptions = computed(() => {
    return {
        series: [
            {
                name: '',
                data: [29, 52, 38, 47, 56]
            },
            {
                name: '',
                data: [71, 71, 71, 71, 71]
            }
        ],
        chart: {
            fontFamily: 'inherit',
            type: 'bar',
            height: 80,
            stacked: true,
            toolbar: {
                show: false
            },
            sparkline: {
                enabled: true
            }
        },
        grid: {
            show: false,
            borderColor: 'rgba(0,0,0,0.1)',
            strokeDashArray: 1,
            xaxis: {
                lines: {
                    show: false
                }
            },
            yaxis: {
                lines: {
                    show: true
                }
            },
            padding: {
                top: 0,
                right: 0,
                bottom: 0,
                left: 0
            }
        },
        colors: ['rgba(var(--v-theme-success))', 'var(--black-black-10, rgba(17, 28, 45, 0.10))'],
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '30%',
                borderRadius: [3],
                borderRadiusApplication: 'end',
                borderRadiusWhenStacked: 'all'
            }
        },
        dataLabels: {
            enabled: false
        },
        xaxis: {
            labels: {
                show: false
            },
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false
            }
        },
        yaxis: {
            labels: {
                show: false
            }
        },
        tooltip: {
            theme: 'dark'
        },
        legend: {
            show: false
        }
    };
});
</script>
<template>
    <v-card elevation="10" class="bg-lightsuccess">
        <v-card-item>
            <div class="d-flex justify-space-between align-center mb-mb-0 mb-3">
                <div class="d-flex ga-3 align-center">
                    <div class="rounded-pill d-inline-flex px-4 py-2 align-center py-0 bg-success">
                        <Icon icon="solar:dollar-minimalistic-bold-duotone" width="25" height="25" class="text-white" />
                    </div>
                    <v-card-title class="text-h6 textSecondary font-weight-medium mb-0">Total Income</v-card-title>
                </div>

                <v-btn flat icon variant="text" rounded="full" class="">
                    <v-avatar size="22">
                        <v-icon>mdi-dots-vertical</v-icon>
                    </v-avatar>
                </v-btn>
            </div>
            <v-row class="d-flex justify-space-between align-end">
                <v-col cols="7" xl="5" class="mb-1">
                    <h2 class="text-h2 mb-3">$23,456</h2>
                    <span class="text-12 rounded-pill px-2 py-1 lh-normal text-muted light-chip border border-muted font-weight-medium">+18% last month</span>
                </v-col>
                <v-col cols="5" xl="5">
                    <div class="rounded-bars">
                        <!-- Chart placeholder -->
                        <div class="chart-placeholder" style="height: 80px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <div class="text-center">
                                <v-icon size="24" color="white">mdi-chart-bar</v-icon>
                            </div>
                        </div>
                    </div>
                </v-col>
            </v-row>
        </v-card-item>
    </v-card>
</template>

<style scoped>
.chart-placeholder {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}
</style>
