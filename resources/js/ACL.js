   
export default class ACL{

    constructor(user){
        this.user = user;
    }


    isAdmin(){
        return this.user.role === 'admin';
    }

    isAccountant(){
        return this.user.role === 'accountant';
    }
    isAdminOrManager(){
        if(this.user.role === 'admin' || this.user.role === 'manager'){
            return true;
        }

    }
}