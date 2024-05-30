<script>
    export default {
        mounted() {
            this.loadData();
        },
        data() {
            return {
                tasksList: [],
                taskTitle: '',
                createErrors: '',
                editing: null,
                editTaskTitle: '',
                editErrors: ''
            }
        },
        methods: {
            loadData: function () {
                axios({
                    method: 'GET',
                    url: `${window.location.origin}/tasks`
                }).then(
                    (response) => {
                        this.tasksList = response.data.data;
                    }
                )
            },

            storeTask: function(title) {
                axios({
                    method: 'POST',
                    url: `${window.location.origin}/tasks`,
                    data: {
                        title: title,
                    }
                }).then(
                    (response) => {
                        this.taskTitle = '';
                        this.tasksList = response.data.data;
                    }
                )
            },

            updateTask: function(task) {
                axios({
                    method: 'PUT',
                    url: `${window.location.origin}/tasks/${task.id}`,
                    data: {
                        title: this.editTaskTitle,
                    }
                }).then(
                    (response) => {
                        this.editing = null;
                        this.editTaskTitle = '';
                        this.tasksList = response.data.data;    
                    }
                )
            },

            removeTask: function(task) {
                axios({
                    method: 'DELETE',
                    url: `${window.location.origin}/tasks/${task.id}`
                }).then(
                    (response) => {
                        this.tasksList = response.data.data;    
                    }
                )
            },

            updateTaskStatus: function(task, flag) {
                axios({
                    method: 'PUT',
                    url: `${window.location.origin}/tasks/${task.id}`,
                    data: {
                        title: task.title,
                        completed: flag
                    }
                }).then(
                    (response) => {
                        this.tasksList = response.data.data;    
                    }
                )
            },

            createTask: function () {
                this.createErrors = '';

                if (this.taskTitle === '') {
                    this.createErrors = 'Task title cannot be empty.';
                    return;
                }

                this.storeTask(this.taskTitle)
            },

            startEdit: function(task) {
                this.editing = task.id;
                this.editTaskTitle = task.title;
                this.$refs[`editInput_${task.id}`][0].focus();
            },

            handleSaveEdit: function(task) {
                this.editErrors = '';

                if (this.editTaskTitle === '') {
                    this.editErrors = 'Task title cannot be empty.';
                    return;
                }

                this.updateTask(task)
            },

            handleInput(task) {
                this.$nextTick(() => {
                    if (event.key === 'Enter') {
                        this.handleSaveEdit(task.id);
                    }
                });
            },

            handleCheckTask(task) {
                this.updateTaskStatus(task, task.completed ? false : true);
            }
        }
    }
</script>

<template>
    <div class="w-full block ">
        <h1 class="border-b-2 pb-2">Your Tasks</h1>
        <div class="task-list pb-4 pt-4 border-b-2">
            <div v-for="task in tasksList" :key="task.id">
                <input v-on:click="handleCheckTask(task)" type="checkbox" class="check-ctr" :checked="task.completed"/>
                <h4 v-show="this.editing !== task.id" class="title-display">{{ task.title }}</h4>
                <input v-show="this.editing === task.id" class="title-display rounded basic-input" type="text" 
                    :ref="`editInput_${task.id}`"
                    v-model="editTaskTitle" 
                    @blur="handleSaveEdit(task)" 
                    @input="handleInput(task)" />
                <span v-if="editErrors && this.editing === task.id" class="input-error rounded">
                    {{ editErrors }}
                </span>
                <span v-on:click="startEdit(task)" class="list-side-ctr side-green rounded hover-effect">&#9998;</span>
                <span v-on:click="removeTask(task)" class="list-side-ctr side-red rounded hover-effect">&#10005;</span>
            </div>
            <div v-if="tasksList.length < 1">
                <h4>No task created yet, use the form below to create now.</h4>
            </div>
        </div>
        <div class="creating-task pt-4">
            <div class="flex w-full align-center justify-start task-input-div">
                <input type="text" v-model="taskTitle"  class="create-inut rounded basic-input" placeholder="Task Title" />
                <button v-on:click="createTask()" class="rounded btn-icon hover-effect" type="button">+</button>
                <span v-if="createErrors" class="input-error rounded">
                    {{ createErrors }}
                </span>
            </div>
        </div>
    </div>
</template>