<?PHP

use App\Http\Controllers\Controller;
use App\Models\User;
use GuzzleHttp\Promise\Create;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Request;

class AuthController extends Controller 
{

    public function register(Request $request){
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' =>[
                'required','confirmed',
                Password::min(8)->mixedCase()->numbers()->symbols()
            ] 
        ]);

        $user = User::create([
            'name'=> $data['name'],
            'email'=> $data['email'],
            'password'=> bcrypt($data['password'])
        ]);
        $token =$user->createToken('main')->plainTextToken;

        return response(['user'=>$user,'token'=>$token],201);   //201 is the status code for created    

    }
}



