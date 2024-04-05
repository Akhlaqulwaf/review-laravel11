<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCandidateRequest;
use App\Http\Resources\CandidateResource;
use App\Models\Candidate;
use App\Models\Job;
use App\Models\Skill;
use App\Models\SkillSet;
use Illuminate\Http\Exceptions\HttpResponseException;

class CandidateController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/candidate",
     *     summary="Create candidate",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  @OA\Property(
     *                      property="name",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="job_id",
     *                      type="integer",
     *                  ),
     *                  @OA\Property(
     *                      property="email",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="phone",
     *                      type="integer",
     *                  ),
     *                  @OA\Property(
     *                      property="skill",
     *                      type="array",
     *                      @OA\Items(
     *                          type="number",
     *                      ),
     *                  ),
     *
     *                  example={"name": "atho", "job_id": 2, "email": "atho@gmail.com", "phone": 84392493324, "skill": {1,2}}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Create Candidate",
     *         @OA\JsonContent(
     *             @OA\Schema(ref="#/components/schemas/result"),
     *             @OA\Examples(example="result", value={"message": "Successfully create candidate"}, summary="An result object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid data",
     *         @OA\JsonContent()
     *     ),
     *     security={{"sanctum":{}}}
     * )
     */
    public function store(StoreCandidateRequest $request)
    {
        $data = $request->validated();

        if (Candidate::where('email', $data['email'])->where('job_id', $data['job_id'])->exists()) {
            throw new HttpResponseException(response([
                'error' => 'Sudah melakukan apply',
            ], 400));
        }

        $candidate = new Candidate($data);
        $job = Job::findOrFail($data['job_id']);
        $candidate->job_id = $job->id;
        $candidate->save();

        $skills = $data['skill'];

        foreach ($skills as $skill) {
            $skillMod = Skill::findOrFail($skill);
            $skillSet = new SkillSet([
                'candidate_id' => $candidate->id,
                'skill_id' => $skillMod->id,
            ]);
            $skillSet->save();
        }

        return response()->json([
            'message' => "Successfully create candidate",
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/candidate",
     *     summary="Get candidate",
     *     @OA\Response(
     *         response=200,
     *         description="Login successfully",
     *         @OA\JsonContent(
     *             @OA\Schema(ref="#/components/schemas/result"),
     *             @OA\Schema(type="array"),
     *             @OA\Examples(
     *                 example="result",
     *                 value={
     *                        "name": "atho",
     *                        "job_id": {"name": "fullstack"},
     *                        "email": "atho@gmail.com",
     *                        "phone": 089811378909
     *                       },
     *                 summary="An result object"
     *             )
     *         )
     *     ),
     *     security={{"sanctum":{}}}
     * )
     */
    public function getCandidates()
    {
        $candidate = Candidate::with('job')->get();

        return CandidateResource::collection($candidate);
    }
    /**
     * @OA\Get(
     *     path="/api/candidate/{id}",
     *     summary="Get candidate",
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         @OA\Schema(type="number"),
     *         @OA\Examples(example="int", value="1", summary="An int value")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successfully",
     *         @OA\JsonContent(
     *             @OA\Schema(ref="#/components/schemas/result"),
     *             @OA\Examples(
     *                 example="result",
     *                 value={
     *                        "name": "atho",
     *                        "job_id": {"name": "fullstack"},
     *                        "email": "atho@gmail.com",
     *                        "phone": 089811378909
     *                       },
     *                 summary="An result object"
     *             )
     *         )
     *     ),
     *     security={{"sanctum":{}}}
     * )
     */
    public function getCandidate(int $id)
    {
        $candidate = Candidate::with('job')->where('id', $id)->first();

        return (new CandidateResource($candidate))->response()->setStatusCode(200);
    }
}
