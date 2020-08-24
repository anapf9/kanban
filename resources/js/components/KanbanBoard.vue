<template>
    <div class="relative p-2 flex overflow-x-auto h-full">

        <!-- Columns (Statuses) -->
        <div
            v-for="status in statuses"
            :key="status.slug"
            class="mr-6 w-4/5 max-w-xs flex-1 flex-shrink-0"
        >
            <div class="rounded-md shadow-md overflow-hidden">
                <div class="p-3 flex justify-between items-baseline bg-blue-800 ">
                    <h4 class="font-medium text-white">
                        {{ status.title }}
                    </h4>
                    <button class="py-1 px-2 text-sm text-orange-500 hover:underline">
                        Add Task
                    </button>
                </div>
                <div class="p-2 bg-blue-100">
                    <AddTaskForm
                        v-if="newTaskForStatus === status.id"
                        :status-id="status.id"
                        :task-added="handleTaskAdded"
                        :task-canceled="closeAddTaskForm"
                    />
                    <!-- Tasks -->
                    <draggable
                        class="flex-1 overflow-hidden"
                        v-model="status.tasks"
                        v-bind="taskDragOptions"
                        @end="handleTaskMoved"
                    >
                        <transition-group
                            class="flex-1 flex flex-col h-full overflow-x-hidden overflow-y-auto rounded shadow-xs"
                            tag="div"
                        >
                            <div
                                v-for="task in status.tasks"
                                :key="task.id"
                                class="mb-3 p-3 h-24 flex flex-col bg-white rounded-md shadow transform hover:shadow-md cursor-pointer"
                            >
                                <span class="block mb-2 text-xl text-gray-900">
                                  {{ task.title }}
                                </span>
                                <p class="text-gray-700 truncate">
                                    {{ task.description }}
                                </p>
                            </div>
                        </transition-group>
                    </draggable>
                    <!-- ./Tasks -->

                    <!-- No Tasks -->
                    <div
                        v-show="!status.tasks.length && newTaskForStatus !== status.id"
                        class="flex-1 p-4 flex flex-col items-center justify-center"
                    >
                        <span class="text-gray-600">No tasks yet</span>
                        <button
                            class="mt-1 text-sm text-orange-600 hover:underline"
                            @click="openAddTaskForm(status.id)"
                        >
                            Add one
                        </button>
                    </div>
                    <!-- ./No Tasks -->
                </div>
            </div>
        </div>
        <!-- ./Columns -->

    </div>
</template>

<script>
    import AddTaskForm from "./AddTaskForm";
    import draggable from "vuedraggable";
    export default {
        props: {
            initialData: Array
        },
        components: {
            AddTaskForm,
            draggable
        },
        data() {
            return {
                statuses: [],
                newTaskForStatus: 0
            };
        },
        computed: {
            taskDragOptions() {
                return {
                    animation: 200,
                    group: "task-list",
                    dragClass: "status-drag"
                };
            }
        },
        mounted() {
            // 'clone' the statuses so we don't alter the prop when making changes
            this.statuses = JSON.parse(JSON.stringify(this.initialData));
        },
        methods: {
            // set the statusId and trigger the form to show
            openAddTaskForm(statusId) {
                this.newTaskForStatus = statusId;
            },
            // reset the statusId and close form
            closeAddTaskForm() {
                this.newTaskForStatus = 0;
            },
            // add a task to the correct column in our list
            handleTaskAdded(newTask) {
                // Find the index of the status where we should add the task
                const statusIndex = this.statuses.findIndex(
                    status => status.id === newTask.status_id
                );

                // Add newly created task to our column
                this.statuses[statusIndex].tasks.push(newTask);

                // Reset and close the AddTaskForm
                this.closeAddTaskForm();
            },
            handleTaskMoved() {
                // Send the entire list of statuses to the server
                axios.put("/tasks/sync", {columns: this.statuses}).catch(err => {
                    console.log(err.response);
                });
            }
        }
    };
</script>
<style scoped>
    .status-drag {
        transition: transform 0.5s;
        transition-property: all;
    }
</style>
