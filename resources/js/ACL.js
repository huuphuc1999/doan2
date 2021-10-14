   
export default class ACL{

    constructor(user){
        this.user = user;
    }


    isAdmin(){
        return this.user.role === 'admin';
    }

    isAccountant(){
        if(this.user.role === 'admin' 
        || this.user.role === 'manager' 
        || this.user.role === 'accountant'){
            return true;
        }
    }
    isAdminOrManager(){
        if(this.user.role === 'admin' || this.user.role === 'manager'){
            return true;
        }

    }
}