<template>
<div class="composer">
    <template v-if="typing != ''">
            <div class="badge badge-pill badge-warning">{{contact.name}} is {{typing}}</div>
    </template>
    <div class="totalcomposer">
        <div class="maincomposer">
            <div class="textareaB" v-if="emoStatus">
                <div class="text">
                    <span v-if="sendreply" class="badge badge-pill white bg-info">
                       In reply of {{reply}}
                    </span>
                    <textarea v-model="message" @keydown.enter="send" placeholder="Message..."></textarea>
                </div>
                <div class="emo">
                    <picker v-if="emoStatus" set="emojione" @select="onInput" title="Pick your emoji…" />
                </div>
            </div>
            <div class="textareaA" v-else>
                <span v-if="sendreply" class="badge badge-pill white bg-info">
                    In reply of: {{reply}}
                </span>
                    <textarea v-model="message" @keydown.enter="send" placeholder="Message..."></textarea>
            </div>
        </div>
        <div class="sidecomposer">
            <div class="text-right">
                <a href="#" @click="toggleEmo" class="fa fa-smile fa-2x red"></a>
            </div>
            <div class="text-right">
                <file-upload
                    :post-action="'/conversation/send'"
                    ref='upload'
                    v-model="files"
                    @input-file="$refs.upload.active = true"
                    :headers="{'X-CSRF-TOKEN': token}"
                    :data="{contact_id:contact.id}">
                    <a href="#" class="fas fa-paperclip fa-2x blue"></a>
                </file-upload> 
            </div>
            <div class="text-right">
                 <a href="#" @click="global" class="fas fa-globe fa-2x green"></a>
            </div>
        </div>
    </div>    
 </div>   
</template>

<script>
import { Picker } from 'emoji-mart-vue-fast';
import 'emoji-mart-vue-fast/css/emoji-mart.css';  
    export default {
       components:{
            Picker
        },
       props:{
            user:{
                type:Object,
                required:true
            },
            contact:{
                type:Object               
            },
            globalmode:{
                type:Boolean,
            },
        },
         data() {
            return {
                message: '',
                typing:'',
                emoStatus:false,
                files:[],
                token:document.head.querySelector('meta[name="csrf-token"]').content,
                //replypart
                sendreply:false,
                reply:'',
                replyId:'',
            };
        },
        methods:{
            send(e){
                e.preventDefault();
               if(this.message==''){
                   return;
               }
               else if(this.sendreply){
                    this.$emit('send',this.message);
                    this.$eventBus.$emit('sendReply',this.sendreply,this.reply,this.message,this.replyId);
                    this.sendreply=false;
                    this.replyId='';
                    this.reply='';
                    this.message='';
               }
               else{
                    this.$emit('send',this.message);
                    this.message='';
                }   
            },
            toggleEmo(){
               this.emoStatus= !this.emoStatus;
            },
            onInput(e){
                if(!e){
                    return false;
                }
                if(!this.message){
                    this.message=e.native;
                }else{
                    this.message=this.message + e.native;
                }
                    //  this.emoStatus=false;
            },
            global(){
                this.globamode =! this.globalmode;
                this.$emit('global',this.globalmode);
            },
        },
        watch:{
            message(){
                Echo.private('chat')
                .whisper('typing', {
                name: this.message
                });
            }
        },
        created(){
                this.$eventBus.$on('reply', (draggingText,draggingId) => {
                this.reply = draggingText;
                this.replyId = draggingId;
                this.sendreply =! this.sendreply; 
            });
        },
        mounted() {
            Echo.private('chat')
                .listen('ChatEvent', (e) => {
                    })             
                .listenForWhisper('typing', (e) => {
                    if(e.name!=''){
                       this.typing='Typing.....';
                    }else{
                        this.typing='';
                    }  
            })
        },     
    }
</script>

<style lang="scss" scoped>
    .composer{
        height: 300px;
        .textareaA textarea{

            width: 100%;
            height: 240px;
            margin: 10px;
            resize: none;
            border-radius: 2px;
            border: 1px solid lightgrey;
        }
        .text textarea{
            width: 100%;
            height:100%;
            border: 1px solid lightgrey;
            position:relative
        }
        .text{
            flex:50%;
        }
        .emo{
            flex:50%;
        }
        .textareaB{
        display: flex;
        }
        // .floating-div{
        //     position: fixed;
        // }
        .totalcomposer{
            height: 250px;
            display:flex;
        }
        .sidecomposer{
            margin:5px;
            padding:5px;
            flex:10%;
        }
        .maincomposer{
            flex:90%;   
        }
    }
</style>
