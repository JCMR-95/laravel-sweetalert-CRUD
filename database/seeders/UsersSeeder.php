use Illuminate\database\seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'name' => 'JC',
                'email' => 'jc@jc.cl',
                'password' => Hash::make('123456789'), // Hashear la contraseña
                'position' => 'Administrador',
            ]
        ];

        foreach ($users as $user) {
            \App\Models\User::create($user);
        }
    }
}
