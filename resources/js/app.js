require('./bootstrap');
import KanbanBoard from "./components/KanbanBoard";
import AddTaskForm from "./components/AddTaskForm"

window.Vue = require("vue");

// Register our components (in the next step)

const app = new Vue({
    el: "#app",
    components: {
        KanbanBoard,
        AddTaskForm
    }
});
