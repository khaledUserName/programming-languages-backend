<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExperienceRequest;
use App\Models\Experience;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;

class ExperienceController extends Controller
{
    use HttpResponses;
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\ExperienceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExperienceRequest $request)
    {
        if (!is_null(Auth::user()->user_id)) {
            return $this->error(['errors' => 'you are not authorize to access this route'], "Unauthorize Error", 401);
        }
        try {
            $experience = Experience::create([...$request->validated(), 'expert_id' => Auth::user()->expert_id]);
        } catch (\Throwable $th) {
            return $this->error(['errors' => $th->getMessage()], 'Server Error', 500);
        }
        return $this->success(['experience' => $experience], 'Experience Created', 201);
    }

    /**
     * Display the specified resource.
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        if (!is_null(Auth::user()->user_id)) {
            return $this->error(['errors' => 'you are not authorize to access this route'], "Unauthorize Error", 401);
        }
        $experiences = Experience::where(['expert_id' => Auth::user()->expert_id])->get();
        if (is_null($experiences))
            return $this->error(['errors' => 'experiences is empty'], 'Experiences is Empty', 404);
        return $this->success(['experience' => $experiences], 'Experiences has been returned', 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\ExperienceRequest  $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(ExperienceRequest $request, int $id)
    {
        if (!is_null(Auth::user()->user_id)) {
            return $this->error(['errors' => 'you are not authorize to access this route'], "Unauthorize Error", 401);
        }
        $experience = Experience::where(['expert_id' => Auth::user()->expert_id, 'experience_id' => $id])->first();
        if (is_null($experience))
            return $this->error(['errors' => "no such experience with the id $id"], 'Not found', 404);
        try {
            $experience->update($request->validated());
        } catch (\Throwable $th) {
            return $this->error(['errors' => $th->getMessage()], 'Server Error', 500);
        }
        return $this->success(['experience' => $experience], 'Experience updated', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        if (!is_null(Auth::user()->user_id)) {
            return $this->error(['errors' => 'you are not authorize to access this route'], "Unauthorize Error", 401);
        }
        $experience = Experience::where(['expert_id' => Auth::user()->expert_id, 'experience_id' => $id])->first();
        if (is_null($experience))
            return $this->error(['errors' => "no such experience with the id $id"], 'Not found', 404);
        try {
            $experience->delete();
        } catch (\Throwable $th) {
            return $this->error(['errors' => $th->getMessage()], 'Server Error', 500);
        }
        return $this->success(['experience' => $experience], 'Experience deleted', 200);
    }
}
