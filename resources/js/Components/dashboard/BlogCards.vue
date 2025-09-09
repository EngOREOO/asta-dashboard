<script setup>
import { ref } from 'vue'

const BlogCardData = ref([
    {
        title: 'Latest Course Updates',
        coveravatar: 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=400&q=80',
        avatar: 'https://images.unsplash.com/photo-1494790108755-2616b612b786?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=40&q=80',
        category: 'Updates',
        read: '5 min read',
        view: '1.2k',
        comments: '24',
        time: '2 hours ago',
        link: '#'
    },
    {
        title: 'Student Success Stories',
        coveravatar: 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=400&q=80',
        avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=40&q=80',
        category: 'Stories',
        read: '3 min read',
        view: '856',
        comments: '18',
        time: '5 hours ago',
        link: '#'
    },
    {
        title: 'New Course Launches',
        coveravatar: 'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=400&q=80',
        avatar: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=40&q=80',
        category: 'Courses',
        read: '4 min read',
        view: '1.5k',
        comments: '32',
        time: '1 day ago',
        link: '#'
    }
]);
</script>
<template>
    <v-row>
        <v-col cols="12" md="4" sm="6" v-for="card in BlogCardData" :key="card.title">
            <v-card elevation="10" rounded="md" class="card-hover">
                <div>
                    <v-img :src="card.coveravatar" height="250px" cover class="rounded-t-md align-end text-right">
                        <v-card-item>
                            <v-chip class="bg-surface text-body-2 font-weight-medium" size="small" rounded="pill" v-text="card.read"></v-chip>
                        </v-card-item>
                    </v-img>
                    <v-avatar size="40" class="mt-n7 mx-6">
                        <img :src="card.avatar" alt="icon" height="40" />
                    </v-avatar>
                    <v-card-item class="pt-4">
                        <v-chip class="text-body-2 font-weight-medium bg-grey100" size="small" rounded="pill" v-text="card.category"></v-chip>
                        <h5 class="text-h5 text-13 my-6 custom-text-primary">
                            <a class="text-decoration-none color-inherits custom-title" :href="card.link">{{ card.title }}</a>
                        </h5>
                        <div class="d-flex align-center justify-space-between">
                            <div>
                                <v-avatar class="" size="18">
                                    <v-icon size="18" class="text-textPrimary">mdi-eye</v-icon>
                                </v-avatar>
                                <span class="text-subtitle-1 ml-2 text-textSecondary" v-text="card.view"></span>
                                <v-avatar class="ml-4" size="18">
                                    <v-icon size="18" class="text-textPrimary">mdi-message-text</v-icon>
                                </v-avatar>
                                <span class="text-subtitle-1 ml-2 text-textSecondary" v-text="card.comments"></span>
                            </div>
                            <div>
                                <v-avatar size="10">
                                    <v-icon size="10" class="text-textPrimary">mdi-circle</v-icon>
                                </v-avatar>
                                <span class="text-subtitle-2 ml-2 text-textSecondary" v-text="card.time"></span>
                            </div>
                        </div>
                    </v-card-item>
                </div>
            </v-card>
        </v-col>
    </v-row>
</template>
