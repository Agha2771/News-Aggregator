<?php namespace News\Repositories\User;


use Illuminate\Support\Facades\Hash;
use News\Abstracts\EloquentRepository;
use News\Models\User;

class UserEloquentRepository extends EloquentRepository implements UserRepositoryInterface
{
  public function __construct()
  {
    $this->model = new User();
  }

  public function create($data){
      $user = new $this->model();
      $user->name = $data['name'];
      $user->email = $data['email'];
      $user->age = $data['age'];
      $user->profile_picture = $data['image'];
      $user->password = Hash::make($data['password']);
      $user->save();
      return $user;
  }

  public function update($data,$id){
      $user = $this->model->where('id',$id)->first();

      if(isset($data['name'])){
          $user->name = $data['name'];
      }

      if(isset($data['age'])){
          $user->age = $data['age'];
      }

      if(isset($data['profileImage']) and $data['profileImage']){
          $user->profile_picture = $data['profileImage'];
      }

      if(isset($data['verificationCode'])){
          $user->verification_code = $data['verificationCode'];
      }

      if(isset($data['verificationCodeSentAt'])){
          $user->verification_code_sent_at = $data['verificationCodeSentAt'];
      }

      if(isset($data['isVerified'])){
          $user->is_verified = $data['isVerified'];
      }

      if(isset($data['password'])){
          $user->password = Hash::make($data['password']);
      }

      $user->save();
      return $user;
  }

  public function getByEmail($email){
      return $this->model->where('email',$email)->first();
  }

    public function resetPassword($data){
        $user = $this->model->where('forgot_token',$data['resetToken'])->first();
        $user->password = Hash::make($data['password']);
        $user->save();
        return $user;
    }

}
