var datascource = {
	'id': '1',
	'name': 'comodo heat',
	'title': 'site tracking system',
	'relationship': {'children_num': 8},
	'children': [
		{
			'id': '2', 'name': 'live', 'title': 'user activity monitor and statistic information', 'relationship': {'children_num': 2, 'parent_num': 1, 'sibling_num': 2},
			'children': [
				{
					'id': '21', 'name': 'live monitor', 'title': 'current visiting liks with view and chat possibilities', 'relationship': {'children_num': 0, 'parent_num': 1, 'sibling_num': 1}
				},
				{
					'id': '22', 'name': 'statistic information', 'title': 'detailed statistic information about selected site', 'relationship': {'children_num': 0, 'parent_num': 1, 'sibling_num': 1}
				}
			]
		},
		{
			'id': '3', 'name': 'heat maps', 'title': 'statistic data and heat maps', 'relationship': {'children_num': 4, 'parent_num': 1, 'sibling_num': 2},
			'children': [
				{
					'id': '31', 'name': 'users', 'title': 'information about tracked users with filter option', 'relationship': {'children_num': 2, 'parent_num': 1, 'sibling_num': 3},
					'children': [
						{
							'id': '311', 'name': 'user information', 'title': 'detailed information about selected user', 'relationship': {'children_num': 3, 'parent_num': 1, 'sibling_num': 1},
							'children': [
								{
									'id': '3111', 'name': 'links', 'title': 'selected user links visit statistic ', 'relationship': {'children_num': 0, 'parent_num': 1, 'sibling_num': 2}
								},
								{
									'id': '3112', 'name': 'activity', 'title': 'selected user links visit history', 'relationship': {'children_num': 0, 'parent_num': 1, 'sibling_num': 2}
								},
								{
									'id': '3113', 'name': 'chat', 'title': 'selected user chat history', 'relationship': {'children_num': 0, 'parent_num': 1, 'sibling_num': 2}
								}
							]
						},
						{
							'id': '312', 'name': 'chat history', 'title': 'chat history with selected user', 'relationship': {'children_num': 0, 'parent_num': 1, 'sibling_num': 1}
						}
					]
				},
				{
					'id': '32', 'name': 'mouse moves', 'title': 'moves heat map with filter option', 'relationship': {'children_num': 0, 'parent_num': 1, 'sibling_num': 3}
				},
				{
					'id': '33', 'name': 'mouse clicks', 'title': 'clicks heat map with filter option', 'relationship': {'children_num': 0, 'parent_num': 1, 'sibling_num': 3}
				},
				{
					'id': '34', 'name': 'mouse animations', 'title': 'recorded session with filter option', 'relationship': {'children_num': 0, 'parent_num': 1, 'sibling_num': 3}
				}
			]
		},
		{
			'id': '4', 'name': 'locations', 'title': 'statistic information about users from tracked locations', 'relationship': {'children_num': 2, 'parent_num': 1, 'sibling_num': 2},
			'children': [
				{
					'id': '41', 'name': 'users by countries', 'title': 'number of users grouped by countries with google map visualization', 'relationship': {'children_num': 0, 'parent_num': 1, 'sibling_num': 1}
				},
				{
					'id': '42', 'name': 'users by specific country', 'title': 'number of users grouped by specific country with google map visualization', 'relationship': {'children_num': 0, 'parent_num': 1, 'sibling_num': 1}
				}
			]
		}

	]
};