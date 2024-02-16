<template>
    <modal :show="show" @close="$emit('close')" class="modal">

        <template slot="header">
            <p slot="header" class="modal-card-title">Delete <span style="text-transform: capitalize;">{{userMulti}}</span>?</p>
        </template>

        <template>
            <div class="field">
                <p> Are you sure? This action will permanently delete the {{userMulti}}. </p>
            </div>
        </template>

        <template slot="footer">
            <button class="button cancel-btn" @click="$emit('close')">Cancel</button>
            <button class="button is-info" @click="request">Delete</button>
        </template>

    </modal>
</template>

<script>
import Modal from './Modal.vue';

export default {
    props: {
        show: {
            type: Boolean,
            required: true,
            default: false,
        },
        multi: {
            default: false,
        },
        selected: {
            default: [],
        },
        user: {
            default: undefined,
        }
    },
    components: {
        Modal,
    },
    computed: {
        userMulti() {
            return this.multi && !this.user ? 'users' : 'user';
        }
    },
    methods: {
        request() {
            axios.delete('/admin/user', {data: { users: this.users() }})
            .then(() => {
                this.$emit('delete');
            })
            .catch((response) => {
            });
        },
        users() {
            //console.log(this.user);
            return this.user ? [this.user] : this.selected;
        }
    }
}
</script>

<style lang="scss">
    .modal {
        .modal-card {
            width: 460px;

            .modal-card-body {
                .field{
                    p:not(.help){
                        text-align: center;
                    }
                }
            }
        }

    }
</style>
