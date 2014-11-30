<?php  namespace Controllers;

use App;
use Input;
use Laracasts\Validation\FormValidationException;
use Response;
use SaleBoss\Repositories\Exceptions\NotFoundException;
use SaleBoss\Repositories\Exceptions\RepositoryException;
use SaleBoss\Services\Authenticator\AuthenticatorInterface;
use SaleBoss\Services\Leads\Commands\LeadDeleteCommand;
use SaleBoss\Services\Leads\Exceptions\AccessDeniedException;
use SaleBoss\Services\Leads\My\Commands\ListCommand;
use SaleBoss\Services\Leads\My\Commands\StoreLeadCommand;
use SaleBoss\Services\Leads\My\Commands\TodayListCommand;
use SaleBoss\Services\Leads\My\Commands\UpdateLeadCommand;
use SaleBoss\Services\Leads\My\Commands\WeekListCommand;

class MyLeadsController extends BaseController
{

	protected $auth;

	public function __construct(
		AuthenticatorInterface $auth
	)
	{
		$this->auth = $auth;
	}

	public function index()
	{
		$list = $this->execute(ListCommand::class, ['user' => $this->auth->user()]);
		return $this->view('admin.pages.lead.my_index')
			->withList($list)
			->withCurrentUser($this->auth->user());
	}

	public function store()
	{
		try {
			$created = $this->execute(StoreLeadCommand::class);
			return Response::json($created);
		} catch (FormValidationException $e) {
			return Response::json($e->getErrors(), 422);
		} catch (RepositoryException $e) {
			return Response::json(['errors' => [[trans('messages.database_error')]]], 422);
		}
	}

	public function destroy($id)
	{
		try {
			$input = ['lead_id' => $id, 'user' => $this->auth->user()];
			$this->execute(LeadDeleteCommand::class, $input);
			return $this->redirectBack()->with('success_message',trans('messages.operation_success'));
		} catch (NotFoundException $e){
			App::abort(404);
		} catch (AccessDeniedException $e) {
			App::abort(404);
		} catch(RepositoryException $e) {
			return $this->redirectBack()->with('error_message',trans('messages.database_error'));
		}
	}

	public function update($id)
	{
        Input::merge(['id' => $id, 'user' => $this->auth->user()]);
		try {
			$this->execute(UpdateLeadCommand::class);
			return $this->redirectBack()->with('success_message',trans('messages.operation_success'));
		}catch (NotFoundException $e){
			App::abort(404);
		}catch (RepositoryException $e){
            print $e->getMessage();exit();
			return $this->redirectBack()->with('error_message',trans('messages.database_error'));
		}catch (FormValidationException $e){
            return $this->redirectBack()->withErrors($e->getErrors());
        }
	}
} 
