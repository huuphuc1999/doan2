   
export default class ACL{

    constructor(user){
        this.user = user;
    }


    isAdmin(){
        return this.user.role === 'admin';
    }

    isEmloyee(){
        return this.user.role === 'emloyee';
    }
    isAdminOrManager(){
        if(this.user.role === 'admin' || this.user.role === 'manager'){
            return true;
        }

    }
}