<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Account;

class AccountController extends Controller
{
    protected $twitterController;

    public function __construct(TwitterController $twitterController)
    {
        $this->twitterController = $twitterController;
    }
    /**
     * Display a listing of the resource.
     */
    public function someMethod(Account $account)
    {
        $account = Account::findOrFail(1);
        // dd($account->password);
        // $user = User::findOrFail($userId);
        $response = $this->twitterController->LoginAccount($account);
        return $response;
    }

    public function index()
    {
        // $user = User::findOrFail($userId);
        $user = Auth::user();
        if($user == null){
            return "Bạn cần đăng nhập để sử dụng chức năng này!";
        }else{
            $accounts = $user->accounts()->paginate(10);
            return view('accounts.index', compact('accounts'));
        }
    }

    public function create()
    {

    }

    public function store(Request $request)
    {
        $request->validate([
            'accounts' => 'required|string',
        ]);

        $duplicateUsernames = [];

        $user = Auth::user();
        $lines = explode("\n", $request->input('accounts'));
        foreach ($lines as $line) {
            $parts = preg_split('/\s+/', trim($line));

            if (count($parts) >= 3) {
                $username = $parts[0];
                $password = $parts[1];
                $twofa = $parts[2];
                $proxy = $parts[3];
                if ($user->accounts()->where('username', $username)->exists()) {
                    $duplicateUsernames[] = $line;
                    continue; // Skip creating this account
                }

                Account::create([
                    'user_id' => $user->id,
                    'username' => $username,
                    'password' => $password,
                    'twofa' => $twofa,
                    'proxy' => $proxy,
                ]);
            }
        }

        // dd($duplicateUsernames);

        if (!empty($duplicateUsernames)) {
            toastr()->error( 'Duplicate usernames found: ' . implode(', ', $duplicateUsernames));
            //dd($duplicateUsernames);
            return redirect()->back()->with('duplicate', $duplicateUsernames);
        }
    
        toastr()->success('Them Account Thanh Cong');
        return redirect()->back();

    }

    public function show(Account $account)
    {

    }

    public function edit(string $id)
    {
        
    }

    public function update(Request $request, Account $account)
    {
        //
        // Validate the request
        $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string',
            'twofa' => 'nullable|string',
            'proxy' => 'nullable|string',
        ]);
        

        // Chỉ cho phép cập nhật tài khoản nếu tài khoản đó thuộc về người dùng hiện tại
        //$this->authorize('update', $account);

        $account->username = $request->input('username');
        $account->password = $request->input('password');
        $account->twofa = $request->input('twofa');
        $account->proxy = $request->input('proxy');
        $account->save();

        return response()->json(['message' => 'Account updated successfully!', 'account' => $account]);
    }

    public function destroy(Account $account)
    {
        $account->delete();
        return response()->json(['message' => 'Account deleted successfully!']);
    }

    public function deleteSelected(Request $request)
    {
        $request->validate([
            'selected_accounts' => 'required|array',
            'selected_accounts.*' => 'exists:accounts,id',
        ]);

        // dd($request);
        Account::whereIn('id', $request->selected_accounts)->delete();
        toastr()->success('Da xoa thanh cong');
        return redirect()->back();
    }
    
}
